<?php

namespace App\Http\Resources;

use App\Language;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VerbFormCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->each(function ($verbForm) {
            $verbForm->append('url');
            $verbForm->structure->append('feature_string');
        });

        return parent::toArray($request);
    }
}
