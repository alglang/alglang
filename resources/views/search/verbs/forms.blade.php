@extends('layouts.app')

@section('content')
    @foreach($results as $result)
        {{ $result->shape }}
    @endforeach
@endsection
