@extends('layouts.app')

@section('content')
    <h1>{{ $language->name }} ({{ $language->algo_code }})</h1>
    <h2>A {{ $language->group->name }} language</h2>

    @if ($language->position)
    <alglang-map
        style="height: 30rem;"
        api-key="{{ config('services.gmaps.key') }}"
        :locations="[{{ json_encode($language->map_data) }}]"
    />
    @endif
@endsection
