<?php

namespace App\Modules\Parsers\Acapta;

use App\Modules\Api\Acapta\AcaptaApi;
use App\Modules\Parsers\Acapta\ParsingStrategy\OnuDataParsingStrategy;
use App\Modules\Parsers\Acapta\ParsingStrategy\OnuStatsParsingStrategy;
use GuzzleHttp\Exception\GuzzleException;

class AcaptaParsingService
{
    public function __construct(private readonly AcaptaApi $api)
    {
    }

    /**
     * @returns array
     * @throws GuzzleException|\Exception
     */
    public function parseOnuData(): array
    {
        $mergedData = [];
        $onuData = $this->getOnuData();
        $onuStats = $this->getOnuStats();
        $uniqueIdentifiers = array_unique(array_merge(array_keys($onuData), array_keys($onuStats)));
        foreach ($uniqueIdentifiers as $identifier) {
            $mergedData []= array_merge($onuData[$identifier] ?? [], $onuStats[$identifier] ?? []);
        }
        return $mergedData;
    }

    /**
     * @throws GuzzleException|\Exception
     */
    private function getOnuData(): array
    {
        $onuRawData = $this->api->getOnuData();
        $parser = new TableParser(new OnuDataParsingStrategy());
        return $parser->parseTable($onuRawData);
    }

    /**
     * @throws GuzzleException|\Exception
     */
    private function getOnuStats(): array
    {
        $onuRawStats = $this->api->getOnuStats();
        $parser = new TableParser(new OnuStatsParsingStrategy());
        return $parser->parseTable($onuRawStats);
    }
}
