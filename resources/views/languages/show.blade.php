@extends('layouts.app')

@section('content')
    @can('edit languages')
    <alglang-language :language="{{ $language }}" :can-edit="true" />
    @else
    <alglang-language :language="{{ $language }}" :can-edit="false" />
    @endcan
@endsection
