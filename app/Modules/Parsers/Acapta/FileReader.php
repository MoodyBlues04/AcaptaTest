<?php

namespace App\Modules\Parsers\Acapta;

class FileReader
{
    /** @var bool|resource */
    private $fp;

    /**
     * @throws \Exception
     */
    public function __construct(string $text)
    {
        $this->fp = fopen("php://temp", 'r+');
        if (false === $this->fp) {
            throw new \Exception("Cannot store text to temp file");
        }
        fputs($this->fp, $text);
        rewind($this->fp);
    }

    public function __destruct()
    {
        fclose($this->fp);
    }

    public function skip(int $linesCnt): void
    {
        for ($i = 0; $i < $linesCnt && $this->readLine(); $i++) {
        }
    }


    public function readLine(): bool|string
    {
        $res = fgets($this->fp);
        return $res === false ? false : ltrim($res);
    }
}
