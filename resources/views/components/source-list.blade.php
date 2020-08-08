<div>
    <ul>
        @foreach($sources as $source)
            <x-preview-link :model="$source">
                {{ $source->short_citation }}
            </x-preview-link>
        @endforeach
    </ul>
</div>
