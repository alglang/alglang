<?php

namespace App\Http\Resources;

use App\Language;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VerbFormCollection extends ResourceCollection
{
    public static function fromLanguage(Language $language)
    {
        $verbForms = $language->verbForms()
                              ->with('mode', 'subject', 'order', 'class')
                              ->get();

        $verbForms->each(function ($verbForm) {
            $verbForm->append('url');
        });

        return new self($verbForms);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
