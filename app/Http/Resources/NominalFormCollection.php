<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NominalFormCollection extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function toArray($request): array
    {
        $this->collection->each(function ($nominalForm) {
            $nominalForm->append('formatted_shape');
        });

        return parent::toArray($request);
    }
}
