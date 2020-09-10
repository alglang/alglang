<?php

namespace App\Presenters;

trait PhonemePresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        $shape = $this->shape ?? $this->ipa;
        return "<i>{$mark}{$shape}</i>";
    }
}
