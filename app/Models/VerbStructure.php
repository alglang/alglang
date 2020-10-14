<?php

namespace App\Models;

use App\Presenters\VerbStructurePresenter;
use App\Traits\GeneratesFromSearchQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class VerbStructure extends Model
{
    use GeneratesFromSearchQuery;
    use HasFactory;
    use VerbStructurePresenter;

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    */

    public $guarded = [];

    /** @var array */
    protected $wildCardProps = [
        'class_abv',
        'order_name',
        'mode_name',
        'subject_name',
        'primary_object_name',
        'secondary_object_name'
    ];

    /** @var array */
    protected $booleanProps = [
        'is_negative',
        'is_diminutive'
    ];

    protected $casts = [
        'is_absolute' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public function matchesStructure(self $other): bool
    {
        foreach ($this->wildCardProps as $property) {
            $ownValue = $this->$property;
            if ($ownValue !== '?' && $ownValue !== $other->$property) {
                return false;
            }
        }

        foreach ($this->booleanProps as $property) {
            $ownValue = $this->$property;
            if ($ownValue !== null && $ownValue !== $other->$property) {
                return false;
            }
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function subject(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function primaryObject(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function secondaryObject(): Relation
    {
        return $this->belongsTo(Feature::class);
    }

    public function class(): Relation
    {
        return $this->belongsTo(VerbClass::class);
    }

    public function order(): Relation
    {
        return $this->belongsTo(VerbOrder::class);
    }

    public function mode(): Relation
    {
        return $this->belongsTo(VerbMode::class);
    }
}
