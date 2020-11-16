@php
    $children = $phoneme->childrenFromLanguage($language);
@endphp

<div>
    @if ($children->count() === 0)
        <div class="px-2 py-1">
            <x-preview-link :model="$phoneme">
                {!! $phoneme->formatted_shape !!}
            </x-preview-link>
            &gt; ?
        </div>
    @else
        @foreach ($children as $child)
            <div class="px-2 py-1 {{ $phoneme->shape !== $child->shape ? 'bg-yellow-200' : '' }}">
                <x-preview-link :model="$phoneme">
                    {!! $phoneme->formatted_shape !!}
                </x-preview-link>
                &gt;
                <x-preview-link :model="$child">
                    {!! $child->formatted_shape !!}
                </x-preview-link>
            </div>
        @endforeach
    @endif
</div>
