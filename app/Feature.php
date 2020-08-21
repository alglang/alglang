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

        if ($this->person === '21') {
            return $this->person;
        }

        $tokens = [
            $this->person,
        ];

        if ($this->number) {
            $tokens[] = ['', 's', 'd', 'p'][$this->number];
        }

        if ($this->obviative_code) {
            $tokens[] = ['', '\'', '"'][$this->obviative_code];
        }

        return implode('', $tokens);
    }
}
