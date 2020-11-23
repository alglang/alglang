<div class="inline relative group">
    @if ($model->url)
    <a
        href="{{ $model->url }}"
        @if($class)
        class="{{ $class }}"
        @endif
    >
    @endif
        {{ $slot }}
    @if ($model->url)
    </a>
    @endif
    @isset($model->preview)
    <div class="absolute hidden lg:group-hover:block z-50 bg-gray-100 shadow-lg w-56 mt-2 p-2">
        {!! $model->preview !!}
    </div>
    @endisset
</div>
