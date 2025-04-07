@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>ONU Devices</h1>

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('onu.stats.index') }}">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            @foreach(array_keys($devices[0] ?? []) as $column)
                                <th>
                                    <div class="mb-2">
                                        <input type="text"
                                               name="{{ $column }}"
                                               value="{{ $filters[$column] ?? '' }}"
                                               class="form-control form-control-sm"
                                               placeholder="Filter {{ ucfirst(str_replace('_', ' ', $column)) }}">
                                    </div>
                                    <a href="{{ request()->fullUrlWithQuery([
                                    'sort_column' => $column,
                                    'sort_direction' => $sort['column'] === $column && $sort['direction'] === 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                        {{ ucfirst(str_replace('_', ' ', $column)) }}
                                        @if($sort['column'] === $column)
                                            {!! $sort['direction'] === 'asc' ? '&#9650;' : '&#9660;' !!}
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($devices as $device)
                            <tr>
                                @foreach($device as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($devices[0] ?? []) }}" class="text-center">No devices found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('onu.stats.index') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
