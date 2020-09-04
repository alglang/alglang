<ul
    class="inline"
    style="pointer-events: all;"
>
    @foreach ($glosses as $gloss)
    <li class="inline-block">
        @if ($gloss->url)
        <a
            href="{{ $gloss->url }}"
            class="inline-block small-caps text-gray-800 hover:text-red-600"
        >
            {{ $gloss->abv }}
        </a>
        @else
        <span class="inline-block">
            {{ $gloss->abv }}
        </span>
        @endif
    </li>
    @endforeach
</ul>
