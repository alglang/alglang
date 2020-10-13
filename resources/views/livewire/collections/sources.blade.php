<div>
    <div class="mb-4 flex justify-between items-end relative">
        @include('components.collection-text-filter')

        @include('components.loading-spinner')

        @include('components.collection-navigation-buttons')
    </div>

    <ul class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-8 ">
        @foreach ($this->items as $source)
        <li class="mr-4 leading-relaxed">
            <x-preview-link :model="$source">
                {{ $source->short_citation }}
            </x-preview-link>
        </li>
        @endforeach
    </ul>
</div>
