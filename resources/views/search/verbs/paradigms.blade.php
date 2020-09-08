@extends('layouts.app')

@section('content')
    <section
        id="verb-paradigm-search-content"
        class="bg-white p-6 w-fit m-auto"
    >
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

@once
@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        new Vue({ el: '#verb-paradigm-search-content' });
    });
</script>
@endpush
@endonce
