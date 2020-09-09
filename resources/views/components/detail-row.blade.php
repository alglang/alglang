<div
    aria-labelledby="{{ $ariaId }}"
    class="p-2 mb-2 flex items-center flex-wrap md:flex-no-wrap"
>
    <p
        id="{{ $ariaId }}"
        class="inline-block w-64 uppercase mb-2 md:mb-0 text-sm md:text-base"
    >
        {{ $label }}
    </p>
    <div class="inline-block w-full">
        {{ $slot }}
    </div>
</div>
