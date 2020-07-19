@extends('layouts.app')

@section('content')
<section class="bg-white p-12 text-center m-auto text-xl" style="width: fit-content">
    <h1 class="text-6xl font-light mb-8">
        Unauthorized
    </h1>

    <p class="mb-4">
        Sorry, it looks like your account isn't allowed to do that.
    </p>
    <p>
        If you believe that you should be able to perform this action, or would like to obtain permission, please contact <a href="mailto:{{ config('app.admin_email') }}">{{ config('app.admin_email') }}</a>.
    </p>
</section>
@endsection
