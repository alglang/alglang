@extends('layouts.app')

@section('content')
<section class="bg-white p-12 text-center m-auto text-xl" style="width: fit-content">
    <h1 class="text-6xl font-light mb-8">
        Page not found
    </h1>

    <p class="mb-4">
        Sorry, we couldn't find that page. If you typed the URL into your browser, please check your spelling.
    </p>
    <p>
        If you believe this page should exist, please contact <a href="mailto:{{ config('app.admin_email') }}">{{ config('app.admin_email') }}</a>.
    </p>
</section>
@endsection
