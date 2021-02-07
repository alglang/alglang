<div class="reflex-graph {{ $isRoot() ? 'reflex-graph--root' : '' }}">
    <div>
        <span class="reflex-graph__language">
            {{ $phoneme->language_code }}
        </span>

        <span class="reflex-graph__phoneme">
            <x-preview-link :model="$phoneme" >
                {!! $phoneme->formatted_shape !!}
            </x-preview-link>
        </span>

        @if ($phoneme->pivot)
            <span class="reflex-graph__environment">
                {{ $phoneme->pivot->environment }}
            </span>
        @endif
    </div>

    @if ($showParents && $phoneme->parents->count() > 0)
        <div class="ml-4">
            <p class="mt-2 font-semibold">Parents</p>
            <ul class="reflex-graph__parents">
                @foreach ($phoneme->parents as $parent)
                    <li>
                        <x-reflex-graph :phoneme="$parent" :root="false" :show-children="false" />
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($showChildren && $phoneme->children->count() > 0)
        <div class="ml-4">
            <p class="mt-2 font-semibold">Children</p>
            <ul class="reflex-graph__children">
                @foreach ($phoneme->children as $child)
                    <li>
                        <x-reflex-graph :phoneme="$child" :root="false" :show-parents="false" />
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
