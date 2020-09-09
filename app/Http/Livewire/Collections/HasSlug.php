<?php

namespace App\Http\Livewire\Collections;

trait HasSlug
{
    /** @var string */
    public $slug;

    public function initializeHasSlug()
    {
        $this->slug = uniqid();
    }
}
