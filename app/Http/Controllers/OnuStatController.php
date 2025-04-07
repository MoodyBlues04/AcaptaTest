<?php

namespace App\Http\Controllers;

use App\Modules\Parsers\Acapta\AcaptaParsingService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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

        $this->saveToStorage($onuData);

        $filtered = $this->applyFilters($onuData['data'], $request);
        $sorted = $this->applySorting($filtered, $request);

        return view('onu_stats.index', [
            'data' => $sorted,
            'fields' => $onuData['fields'],
            'filters' => $request->all(),
            'sort' => [
                'column' => $request->get('sort_column'),
                'direction' => $request->get('sort_direction', 'asc')
            ]
        ]);
    }

    private function saveToStorage(array &$data): void
    {
        $fileName = 'onu_stats-' . time() . '.json';
        Storage::put('onu_stats/' . $fileName, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function applyFilters(array &$data, Request $request): array
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

    private function applySorting(array &$data, Request $request): array
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
