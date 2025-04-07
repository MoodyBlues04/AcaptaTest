<?php

namespace App\Modules\Parsers\Acapta\ParsingResult;

class OnuDataParsingStrategy extends ParsingStrategy
{
    protected array $headersMapping = [
        'IntfName' => 'interface',
        'VendorID' => 'vendor_id',
        'ModelID' => 'model_id',
        'SN' => 'sn',
        'LOID' => 'loid',
        'Status' => 'status',
        'Config Status' => 'config_status',
        'Active Time' => 'active_time',
    ];

    public function getPrefixLinesCount(): int
    {
        return 2;
    }

    public function emptyLinePrefix(): string
    {
        return 'OLT-Leninskoe-GPON#';
    }

    public function columnMarginSize(): int
    {
        return 1;
    }
}
