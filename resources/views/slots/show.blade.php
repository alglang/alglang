@extends('layouts.app')

@section('content')
    <alglang-slot :morph-slot="{{ $slot }}" />
@endsection
