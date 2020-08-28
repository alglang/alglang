<?php

namespace App\Presenters;

trait FormPresenter
{
    public function getFormattedShapeAttribute(): string
    {
        $mark = $this->isReconstructed() ? '*' : '';
        return "<i>{$mark}{$this->getMarkedUpShape()}</i>";
    }

    protected function getMarkedUpShape(): string
    {
        return preg_replace(
            '/([A-Z])/',
            '<span class="not-italic">$1</span>',
            $this->shape
        );
    }
}
