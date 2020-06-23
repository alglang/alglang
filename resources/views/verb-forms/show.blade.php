<h1>{{ $verbForm->shape }}</h1>
<h2>belonging to {{ $verbForm->language->name }}</h2>

<p>
    <span>{{ $verbForm->subject->name }}</span>
    <span>{{ $verbForm->class->abv }}</span>
    <span>{{ $verbForm->order->name }}</span>
    <span>{{ $verbForm->mode->name }}</span>
</p>

<p>{{ $verbForm->historical_notes }}</p>
<p>{{ $verbForm->allomorphy_notes }}</p>
<p>{{ $verbForm->usage_notes }}</p>
<p>{{ $verbForm->private_notes }}</p>
