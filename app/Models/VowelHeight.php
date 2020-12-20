<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VowelHeight extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected $primaryKey = 'name';

    protected $keyType = 'str';

    public $timestamps = false;
}
