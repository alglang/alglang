<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MorphemeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->each(function ($morpheme) {
            $morpheme->append(
                'glosses',
                'disambiguator',
                'formatted_shape'
            );
        });

        return parent::toArray($request);
    }
}
