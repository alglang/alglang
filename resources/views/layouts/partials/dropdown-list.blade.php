<ul
    class="absolute hidden group-hover:block group-focus-within:block py-1 
           border-t border-gray-100 bg-gray-900 whitespace-no-wrap z-50 {{ $class ?? '' }}"
    role="menu"
    aria-orientation="vertical"
    {{ isset($labelledby) ? "aria-labelledby=$labelledby" : '' }}
>
    @foreach ($links as $label => $url)
        <li>
            <a
                href="{{ $url }}"
                class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700"
            >
                {{ $label }}
            </a>
        </li>
    @endforeach

    <li>
        {{ $slot ?? '' }}
    </li>
</ul>
