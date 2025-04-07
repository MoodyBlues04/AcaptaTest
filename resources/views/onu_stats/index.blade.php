<?php
/**
 * @var array<array<string,string>> $data
 * @var string[] $fields
 * @var string[] $filters
 * @var array<string,string> $sort
 */
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>ONU Devices</h1>

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('onu.stats.index') }}">
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('onu.stats.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            @foreach($fields as $field)
                                <th>
                                    <div class="mb-2">
                                        <input type="text"
                                               name="{{ $field }}"
                                               value="{{ $filters[$field] ?? null }}"
                                               class="form-control form-control-sm"
                                               placeholder="Filter {{ ucfirst(str_replace('_', ' ', $field)) }}">
                                    </div>
                                    <a href="{{ request()->fullUrlWithQuery([
                                    'sort_column' => $field,
                                    'sort_direction' => $sort['column'] === $field && $sort['direction'] === 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                                        @if($sort['column'] === $field)
                                            {!! $sort['direction'] === 'asc' ? '&#9650;' : '&#9660;' !!}
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                @foreach($fields as $field)
                                    <td>{{ $row[$field] ?? '--' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection
