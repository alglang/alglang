<?php

namespace App\Presenters;

trait FormPresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->markUpShape($this->shape)}</i>";
    }

    public function getFormattedPhonemicShapeAttribute(): ?string
    {
        if (!$this->phonemic_shape) {
            return null;
        }

        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->markUpShape($this->phonemic_shape)}</i>";
    }

    protected function markUpShape($shape): string
    {
        return preg_replace(
            '/([A-Z])/',
            '<span class="not-italic">$1</span>',
            $shape
        );
    }
}
