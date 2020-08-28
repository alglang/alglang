<div>
    <ul>
        @foreach($sources as $source)
            <x-preview-link :model="$source">
                {{ $source->short_citation }}
                @if($source->attribution && $source->attribution->extra_info)
                    ({{ $source->attribution->extra_info }})
                @endif
            </x-preview-link>
        @endforeach
    </ul>
</div>
