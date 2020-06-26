@extends('layouts.app')

@section('content')
    <alglang-language :language="{{ $language }}" gmaps-api-key="{{ config('services.gmaps.key') }}" />
@endsection
