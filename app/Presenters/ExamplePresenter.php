<?php

namespace App\Presenters;

trait ExamplePresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->shape}</i>";
    }

    public function getFormattedPhonemicShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->phonemic_shape}</i>";
    }
}
