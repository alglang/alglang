<?php

namespace App\Presenters;

trait ExamplePresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->shape}</i>";
    }
}
