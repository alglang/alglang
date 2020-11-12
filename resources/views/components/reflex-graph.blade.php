<div class="reflex-graph {{ $isRoot() ? 'reflex-graph--root' : '' }}">
    <div class="reflex-graph__phoneme">
        <x-preview-link :model="$phoneme" >
            {!! $phoneme->formatted_shape !!}
        </x-preview-link>
    </div>

    <div class="reflex-graph__language">
        {{ $phoneme->language_code }}
    </div>

    @if ($phoneme->pivot)
        <div class="reflex-graph__environment">
            {{ $phoneme->pivot->environment }}
        </div>
    @endif

    @if ($showParents)
        <ul class="reflex-graph__parents">
            @foreach ($phoneme->parents as $parent)
                <li>
                    <x-reflex-graph :phoneme="$parent" :root="false" :show-children="false" />
                </li>
            @endforeach
        </ul>
    @endif

    @if ($showChildren)
        <ul class="reflex-graph__children">
            @foreach ($phoneme->children as $child)
                <li>
                    <x-reflex-graph :phoneme="$child" :root="false" :show-parents="false" />
                </li>
            @endforeach
        </ul>
    @endif
</div>
