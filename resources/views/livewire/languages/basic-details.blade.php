<div>
    @if($language->alternate_names)
    <x-detail-row label="Also known as">
        <ul class="comma-list">
            @foreach($language->alternate_names as $alternate_name)
            <li class="inline">{{ $alternate_name }}</li>
            @endforeach
        </ul>
    </x-detail-row>
    @endif

    <x-detail-row label="Algonquianist code">
        <p>{{ $language->code }}</p>
    </x-detail-row>

    @if($language->iso)
    <x-detail-row label="ISO">
        <p>{{ $language->iso }}</p>
    </x-detail-row>
    @endif

    <x-detail-row label="Group">
        <p>
            <x-preview-link :model="$language->group">
                {{ $language->group->name }}
            </x-preview-link>
        </p>
    </x-detail-row>

    @if($language->parent)
    <x-detail-row label="Parent">
        <p>
            <x-preview-link :model="$language->parent">
                {{ $language->parent->name }}
            </x-preview-link>
        </p>
    </x-detail-row>
    @endif

    @if($language->children->count() > 0)
    <x-detail-row label="Direct descendants">
        <ul>
            @foreach($language->children as $child)
            <li>
                <x-preview-link :model="$child">
                    {{ $child->name }}
                </x-preview-link>
            </li>
            @endforeach
        </ul>
    </x-detail-row>
    @endif

    @if ($language->notes)
    <alglang-detail-row label="Notes">
        {!! $language->notes !!}
    </alglang-detail-row>
    @endif

    @if ($language->position)
    <x-detail-row label="Location">
        <livewire:map :locations="[$language]" />
    </x-detail-row>
    @endif
</div>
