<v-popover
    :delay="{ show: 500, hide: 100 }"
    trigger="{{ isset($model->preview) ? 'hover' : 'manual' }}"
    class="inline"
>
    <a
        href="{{ $model->url }}"
        @if($class)
        class="{{ $class }}"
        @endif
    >
        {{ $slot }}
    </a>
    @isset($model->preview)
        <template slot="popover">
            {!! $model->preview !!}
        </template>
    @endisset
</v-popover>
