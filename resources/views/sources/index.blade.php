@extends('layouts.app')

@section('content')
    <section class="bg-white p-6">
        <h1 class="text-2xl mb-4 md:mb-6">
            Bibliography
        </h1>

        <livewire:collections.sources></livewire:collections.sources>
    </section>
@endsection
