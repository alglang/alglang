<div>
    <div class="mb-4 flex justify-between items-end relative">
        @include('components.collection-text-filter')

        @include('components.loading-spinner')

        @include('components.collection-navigation-buttons')
    </div>

    <ul class="flex justify-center md:justify-start flex-wrap">
        @foreach ($this->examples as $example)
        <li class="mr-4">
            <x-example-card :example="$example"></x-example-card>
        </li>
        @endforeach
    </ul>
</div>
