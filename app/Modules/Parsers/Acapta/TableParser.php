<?php

namespace App\Modules\Parsers\Acapta;

use App\Modules\Parsers\Acapta\ParsingStrategy\ParsingStrategy;

class TableParser
{
    private readonly ParsingStrategy $parsingStrategy;
    private ?FileReader $reader = null;
    private array $headers = [];
    private array $columnSizes = [];
    private int $lineSize = 0;

    public function __construct(ParsingStrategy $dto)
    {
        $this->parsingStrategy = $dto;
    }

    /**
     * @throws \Exception
     */
    public function parseTable(string $rawTable): array
    {
        $this->reader = new FileReader($rawTable);

        $this->parseHeaders();
        while (($values = $this->readLineWithHeaders()) !== null) {
            $this->parsingStrategy->addRow($values);
        }

        return $this->parsingStrategy->getData();
    }


    private function parseHeaders(): void
    {
        $this->reader->skip($this->parsingStrategy->getPrefixLinesCount());
        $headersRow = $this->reader->readLine();
        $columnsSizesRow = $this->reader->readLine();
        $spacesCount = $this->parsingStrategy->columnMarginSize();
        $this->columnSizes = array_map(fn (string $substr) => strlen($substr)+$spacesCount, explode(str_repeat(' ', $spacesCount), $columnsSizesRow));
        $this->columnSizes[sizeof($this->columnSizes) - 1] -= $spacesCount;
        $this->lineSize = array_sum($this->columnSizes);
        $this->headers = $this->splitLine($headersRow);

        if (!$this->parsingStrategy->isValidHeaders($this->headers)) {
            throw new \Exception('Invalid table headers: ' . json_encode($this->headers));
        }
    }

    private function readLineWithHeaders(): ?array
    {
        $values = $this->parseLine();
        if (empty($values)) {
            return null;
        }
        $valuesWithHeaders = [];
        foreach ($values as $idx => $value) {
            $valuesWithHeaders[$this->headers[$idx]] = $value;
        }
        return $valuesWithHeaders;
    }

    private function parseLine(): array
    {
        $line = '';
        while (strlen($line) < $this->lineSize && false !== ($tmpLine = $this->reader->readLine())) {
            if (empty($tmpLine)) {
                $line .= "\r\n"; // костыль для обработки пустой строки в stats
                continue;
            }
            if (str_contains($tmpLine, $this->parsingStrategy->emptyLinePrefix())) {
                break;
            }
            if (preg_match('/^.*active[\r\n]?$/', $line)) { // костыль для выравнивания при переносе в data
                $tmpLine = '   ' . $tmpLine;
            }
            $line = trim($line) . $tmpLine;

            if (preg_match('/^.*--\r?\n$/', $line)) { // bug in onu stats formatting: normal values are aligned by col size, but not '--'
                break;
            }
        }
        return $this->splitLine(trim($line));
    }

    private function splitLine(string $line): array
    {
        $lineParts = [];
        $offset = 0;
        for ($wordIdx = 0; $offset < strlen($line) && $wordIdx < sizeof($this->columnSizes); $wordIdx++) {
            $wordLen = $this->columnSizes[$wordIdx];
            $lineParts []= trim(substr($line, $offset, $wordLen));
            $offset += $wordLen;
        }
        return $lineParts;
    }
}
