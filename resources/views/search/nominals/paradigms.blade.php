@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 w-fit m-auto">
        <h1 class="text-2xl mb-6">
            Nominal paradigm search
        </h1>

        <p class="mb-4">
            Select at least one language or at least one paradigm:
        </p>

        <alglang-nominal-paradigm-search
            :languages="{{ $languages }}"
            :paradigm-types="{{ $paradigmTypes }}"
        ></alglang-nominal-paradigm-search>
    </section>
@endsection