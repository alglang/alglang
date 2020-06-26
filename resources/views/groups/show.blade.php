@extends('layouts.app')

@section('content')
    <alglang-group api-key="{{ config('services.gmaps.key') }}" :group="{{ $group->toJson() }}"></alglang-group>
@endsection
