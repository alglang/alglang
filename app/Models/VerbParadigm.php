<?php

namespace App\Models;

use App\Search\VerbSearch;
use Illuminate\Support\Collection;

/**
 * @property string $class_abv
 * @property string $order_name
 * @property string $mode_name
 * @property string $is_negative
 * @property string $is_diminutive
 * @property string $subject_name
 * @property string $primary_object_name
 * @property string $secondary_object_name
 */
class VerbParadigm extends VerbStructure
{
    /** @var int */
    public $language_code;

    /*
    |--------------------------------------------------------------------------
    | Constructors
    |--------------------------------------------------------------------------
    |
    */

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['language_code'])) {
            throw new \InvalidArgumentException("'language_code' cannot be null");
        }

        parent::__construct(array_merge($attributes, [
            'subject_name' => isset($attributes['subject_name']) ? '?' : null,
            'primary_object_name' => isset($attributes['primary_object_name']) ? '?' : null,
            'secondary_object_name' => isset($attributes['secondary_object_name']) ? '?' : null
        ]));

        $this->language_code = $attributes['language_code'];
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getFormsAttribute(): Collection
    {
        $query = [
            'languages' => [$this->language_code],
            'modes' => [$this->mode_name],
            'orders' => [$this->order_name],
            'classes' => [$this->class_abv],
            'negative' => (bool) $this->is_negative,
            'diminutive' => (bool) $this->is_diminutive,
            'subject' => $this->subject_name === '?',
            'primary_object' => $this->primary_object_name === '?',
            'secondary_object' => $this->secondary_object_name === '?',
        ];

        return VerbSearch::search($query);
    }

    public function getLanguageAttribute(): Language
    {
        return Language::find($this->language_code);
    }

    public function getNameAttribute(): string
    {
        $tokens = [
            $this->class_abv,
            $this->order_name,
            $this->mode_name
        ];

        if ($this->is_negative && $this->is_diminutive) {
            $tokens[] = '(negative, diminutive)';
        } elseif ($this->is_negative) {
            $tokens[] = '(negative)';
        } elseif ($this->is_diminutive) {
            $tokens[] = '(diminutive)';
        }

        return implode(' ', $tokens);
    }

    public function getUrlAttribute(): string
    {
        return route('verbParadigms.show', [
            'language' => $this->language->slug,
            'class' => $this->class_abv,
            'order' => $this->order_name,
            'mode' => $this->mode_name,
            'negative' => $this->is_negative,
            'diminutive' => $this->is_diminutive,
            'subject' => $this->subject_name,
            'primary_object' => $this->primary_object_name,
            'secondary_object' => $this->secondary_object_name,
        ], false);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    public static function generate(Language $language, VerbStructure $structure): self
    {
        return new VerbParadigm(array_merge(['language_code' => $language->code], $structure->toArray()));
    }
}
