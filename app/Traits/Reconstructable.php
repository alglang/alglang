<?php

namespace App\Traits;

trait Reconstructable
{
    public function isReconstructed(): bool
    {
        return $this->language && $this->language->reconstructed;
    }
}
