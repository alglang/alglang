@extends('layouts.app')

@section('content')
    <section class="bg-white p-6">
        <h1 class="text-2xl mb-4 md:mb-6">
            Bibliography
        </h1>

        <alglang-sources url="/api/sources?per_page=200" />
    </section>
@endsection
