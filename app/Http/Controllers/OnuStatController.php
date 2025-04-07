<?php

namespace App\Http\Controllers;

use App\Modules\Parsers\Acapta\AcaptaParsingService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class OnuStatController extends Controller
{
    public function __construct(private readonly AcaptaParsingService $service)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function index(Request $request): View
    {
        $onuData = Cache::remember('onu_stats', 30, function () {
            return $this->service->parseOnuData();
        });

        $filtered = $this->applyFilters($onuData, $request);
        $sorted = $this->applySorting($filtered, $request);

        return view('onu_stats.index', [
            'devices' => $sorted,
            'filters' => $request->all(),
            'sort' => [
                'column' => $request->get('sort_column'),
                'direction' => $request->get('sort_direction', 'asc')
            ]
        ]);
    }

    private function applyFilters($data, $request)
    {
        return collect($data)->filter(function ($item) use ($request) {
            foreach ($request->all() as $key => $value) {
                if ($value && array_key_exists($key, $item)) {
                    $itemValue = is_string($item[$key]) ? strtolower($item[$key]) : $item[$key];
                    $filterValue = strtolower($value);

                    if (!str_contains($itemValue, $filterValue)) {
                        return false;
                    }
                }
            }
            return true;
        })->values()->toArray();
    }

    private function applySorting($data, $request)
    {
        $column = $request->get('sort_column');
        $direction = $request->get('sort_direction', 'asc');

        if ($column && array_key_exists($column, $data[0] ?? [])) {
            usort($data, function ($a, $b) use ($column, $direction) {
                $result = $a[$column] <=> $b[$column];
                return $direction === 'desc' ? -$result : $result;
            });
        }

        return $data;
    }
}
