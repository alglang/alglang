<h1>{{ $morpheme->shape }}</h1>
<h2>{{ $morpheme->language->name }}</h2>

<div>
    <label>Slot</label>
    <p>{{ $morpheme->slot->abv }}</p>
</div>

<div>
    <label>Gloss</label>
    <p>
        {{ $morpheme->gloss }}
    </p>
</div>

<div>
    <label>Historical Notes</label>
    <p>
        {{ $morpheme->historical_notes }}
    </p>
</div>

<div>
    <label>Allomorphy Notes</label>
    <p>
        {{ $morpheme->allomorphy_notes }}
    </p>
</div>

<div>
    <label>Private Notes</label>
    <p>
        {{ $morpheme->private_notes }}
    </p>
</div>
