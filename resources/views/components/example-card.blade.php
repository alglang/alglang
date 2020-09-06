<div class="relative mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow w-48">
    <a
        href="{{ $example->url }}}"
        class="absolute left-0 right-0 top-0 bottom-0"
        aria-label="{{ $example->shape }}"
    ></a>

    <div
        class="relative z-10 leading-relaxed"
        style="pointer-events: none;"
    >
        <p class="text-lg">
            {!! $example->formatted_shape !!}
        </p>
    </div>
</div>
