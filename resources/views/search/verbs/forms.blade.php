@extends('layouts.app')

@section('content')
    @foreach($results as $result)
        {{ $result->language->name }}
        {{ $result->shape }}
    @endforeach
@endsection
