@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 w-fit m-auto">
        <h1 class="text-2xl mb-4">
            Verb paradigm search
        </h1>

        <alglang-verb-paradigm-search
            :languages="{{ $languages }}"
            :classes="{{ $classes }}"
            :orders="{{ $orders }}"
        ></alglang-verb-paradigm-search>
    </section>
@endsection
