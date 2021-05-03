<div class="contents lg:flex lg:flex-col lg:w-fit">
    <label
        class="block uppercase flex-1 text-xs font-semibold bg-gray-300 text-right
               text-gray-700 p-2 md:px-3 flex justify-end lg:justify-start items-center"
        for="{{ $for }}"
    >
        {{ $label }}
    </label>
    {{ $slot }}
</div>
