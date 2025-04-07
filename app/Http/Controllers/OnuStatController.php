<?php

namespace App\Http\Controllers;

use App\Modules\Api\Acapta\AcaptaApi;
use App\Modules\Parsers\Acapta\ParsingResult\OnuDataParsingStrategy;
use App\Modules\Parsers\Acapta\ParsingResult\OnuStatsParsingStrategy;
use App\Modules\Parsers\Acapta\TableParser;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class OnuStatController extends Controller
{
    public function __construct(private readonly AcaptaApi $api)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function index()
    {
        echo '<pre>';
        $onuData = $this->api->getOnuData();
        var_dump($onuData);
        $parser = new TableParser(new OnuDataParsingStrategy());
        var_dump($parser->parseTable($onuData));

//        $onuStats = $this->api->getOnuStats();
//        var_dump($onuStats);
//        $parser = new TableParser(new OnuStatsParsingStrategy());
//        var_dump($parser->parseTable($onuStats));
    }
}
