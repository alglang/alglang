@extends('layouts.app')

@section('content')
<section class="bg-white p-12 text-center m-auto text-xl" style="width: fit-content">
    <h1 class="text-6xl font-light mb-8">
        Internal server error
    </h1>

    <p class="mb-4">
        Looks like you found a bug!
    </p>

    <p>
        Please contact <a href="mailto:{{ config('app.admin_email') }}">{{ config('app.admin_email') }}</a> to report it.
    </p>
</section>
@endsection
