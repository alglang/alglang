<?php

namespace App\Presenters;

trait MorphemePresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->shape}</i>";
    }
}
