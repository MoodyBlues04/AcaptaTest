<?php

namespace App\Modules\Parsers\Acapta\ParsingResult;

abstract class ParsingStrategy
{
    protected array $data = [];

    /**
     * Defines map between table headers and serialized object's properties.
     * @property array<string,string> $headersMapping
     */
    protected array $headersMapping = [];

    /**
     * @param array<string,string> $row Header => value array
     * @return void
     */
    public function addRow(array $row): void
    {
        $correctRow = [];
        foreach ($row as $header => $value) {
            if (!$this->isValidHeader($header)) {
                throw new \InvalidArgumentException("Invalid header: '$header'");
            }
            $correctRow[$this->headersMapping[$header]] = $value;
        }
        $this->data []= $correctRow;
    }

    public function isValidHeaders(array $headers): bool
    {
        return !array_diff($headers, array_keys($this->headersMapping));
    }

    public function getHeadersCount(): int
    {
        return sizeof($this->headersMapping);
    }

    protected function isValidHeader(string $header): bool
    {
        return isset($this->headersMapping[$header]);
    }

    public abstract function columnMarginSize(): int;
    public abstract function getPrefixLinesCount(): int;
    public abstract function emptyLinePrefix(): string;

    public function getData(): array {
        return $this->data;
    }
}
