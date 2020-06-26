@extends('layouts.app')

@section('content')
    <alglang-group gmaps-api-key="{{ config('services.gmaps.key') }}" :group="{{ $group->toJson() }}"></alglang-group>
@endsection
