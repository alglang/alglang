<div
    class="x-morpheme-card relative mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow w-fit"
    style="min-width: 12rem"
>
    <a
        href="{{ $morpheme->url }}"
        class="absolute left-0 right-0 top-0 bottom-0"
    >
    </a>
    <div
        class="relative z-10"
        style="pointer-events: none;"
    >
        <a
            href="{{ $morpheme->slot->url }}"
            class="text-xs font-semibold"
            style="color: {{ $morpheme->slot->colour }}; pointer-events: all;"
        >
            {{ $morpheme->slot->abv }}
        </a>

        <p class="text-xl py-2">
            {!! $morpheme->formatted_shape !!}<!--
         --><span class="text-xs align-super">{{ $morpheme->disambiguator + 1 }}</span>
        </p>

        <x-gloss-field :glosses="$morpheme->glosses" />
    </div>
</div>
