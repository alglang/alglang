<div>
    <div class="mb-4">
        @include('components.loading-spinner')

        @include('components.collection-navigation-buttons')
    </div>

    <ul class="flex justify-center md:justify-start flex-wrap">
        @foreach ($this->items as $morpheme)
        <li class="mr-4">
            <x-morpheme-card :morpheme="$morpheme"></x-morpheme-card>
        </li>
        @endforeach
    </ul>
</div>
