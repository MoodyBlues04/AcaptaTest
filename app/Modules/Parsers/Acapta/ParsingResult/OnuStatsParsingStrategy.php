<?php

namespace App\Modules\Parsers\Acapta\ParsingResult;

class OnuStatsParsingStrategy extends ParsingStrategy
{
    protected array $headersMapping = [
        'IntfName' => 'interface',
        'Temp(degree)' => 'temperature',
        'Volt(V)' => 'voltage',
        'Bias(mA)' => 'bias',
        'TxPow(dBm)' => 'tx_power',
        'RxPow(dBm)' => 'rx_power',
    ];
    public function getPrefixLinesCount(): int
    {
        return 1;
    }

    public function emptyLinePrefix(): string
    {
        return 'OLT-Leninskoe-GPON#';
    }

    public function columnMarginSize(): int
    {
        return 2;
    }
}
