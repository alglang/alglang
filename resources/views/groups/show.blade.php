@extends('layouts.app')

@section('content')
    <alglang-group :group="{{ $group->toJson() }}"></alglang-group>
@endsection
