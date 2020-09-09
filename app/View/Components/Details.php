<?php

namespace App\View\Components;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\View\Component;

class Details extends Component
{
    /** @var string */
    public $title;

    /** @var Arrayable|array */
    public $pages;

    /**
     * Create a new component instance.
     *
     * @param Arrayable|array $pages
     *
     * @return void
     */
    public function __construct(string $title, $pages)
    {
        $this->title = $title;
        $this->pages = $pages;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.details');
    }
}
