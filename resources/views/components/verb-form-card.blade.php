<div
    class="relative mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow w-48"
>
    <a
        href="{{ $form->url }}"
        class="absolute left-0 right-0 top-0 bottom-0"
        aria-label="{{ $form->shape ?? 'No form' }}"
    ></a>

    <div
        class="relative z-10 leading-relaxed"
        style="pointer-events: none;"
    >
        <p class="text-xs font-semibold text-gray-700">
            {!! $form->structure->feature_string !!}
        </p>
        <p class="text-lg">
            {!! $form->formatted_shape ?? 'No form' !!}
        </p>
        <p class="text-xs font-semibold tracking-wide text-gray-700">
            {{ $form->structure->class_abv }}
            {{ $form->structure->order_name }}
            {{ $form->structure->mode_name }}
        </p>
    </div>
</div>
