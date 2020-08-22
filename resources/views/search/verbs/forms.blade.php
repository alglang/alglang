@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 w-fit m-auto">
        <h1 class="text-2xl mb-4">
            Verb form search
        </h1>

        <alglang-verb-form-search
            :languages="{{ $languages }}"
            :classes="{{ $classes }}"
            :orders="{{ $orders }}"
            :modes="{{ $modes }}"
            :features="{{ $features }}"
        ></alglang-verb-form-search>
    </section>
@endsection
