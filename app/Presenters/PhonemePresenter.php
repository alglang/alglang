<?php

namespace App\Presenters;

trait PhonemePresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        $shape = $this->shape ?? $this->ipa;
        $markup = "<i>{$mark}{$shape}</i>";

        if ($this->is_marginal) {
            $markup = "({$markup})";
        }

        return $markup;
    }
}
