<div>
    <table class="text-center">
        <tbody>
            <tr class="hyphenated">
                @foreach($morphemes as $morpheme)
                    <td class="px-3 first:pl-0 pb-1">
                        <a
                            href="{{ $morpheme->url }}"
                            class="hover:filter-brightness-5/4"
                            style="color: {{ $morpheme->slot->colour }};"
                        >
                            {{ trim($morpheme->shape, '-') }}
                        </a>
                    </td>
                @endforeach
            </tr>

            <tr>
                @foreach($morphemes as $morpheme)
                    <td class="px-3 first:pl-0" style="color: {{ $morpheme->slot->colour }};">
                        <alglang-gloss-field :value="{{ $morpheme->glosses }}" />
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
