<?php

namespace App\Http\Livewire\Collections;

trait HasSlug
{
    /** @var string */
    public $slug;

    public function initializeHasSlug(): void
    {
        $this->slug = uniqid();
    }
}
