<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $primaryKey = 'name';
    protected $keyType = 'str';
    public $incrementing = false;
    protected $guarded = [];

    public function getNameAttribute(?string $value): string
    {
        if (isset($value)) {
            return $value;
        }

        // First person exclusive doesn't need any more information
        if ($this->person === '21') {
            return $this->person;
        }

        return implode('', [
            $this->person,
            ['', 's', 'd', 'p'][$this->number ?? 0],
            ['', '\'', '"'][$this->obviative_code ?? 0]
        ]);
    }
}
