<div>
    <div class="mb-4 flex justify-between items-end relative">
        @include('components.collection-text-filter')

        @include('components.loading-spinner')

        @include('components.collection-navigation-buttons')
    </div>

    <ul class="flex justify-center md:justify-start flex-wrap">
        @foreach ($this->items as $form)
        <li class="mr-4">
            <x-nominal-form-card :form="$form"></x-nominal-form-card>
        </li>
        @endforeach
    </ul>
</div>
