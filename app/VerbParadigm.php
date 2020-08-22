<?php

namespace App;

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
    public $language_id;

    /** @var Collection */
    private $_forms;

    /** @var Language */
    private $_language;

    public function __construct(array $attributes = [])
    {
        parent::__construct(array_merge($attributes, [
            'subject_name' => isset($attributes['subject_name']) ? '?' : null,
            'primary_object_name' => isset($attributes['primary_object_name']) ? '?' : null,
            'secondary_object_name' => isset($attributes['secondary_object_name']) ? '?' : null
        ]));

        if (isset($attributes['language_id'])) {
            $this->language_id = $attributes['language_id'];
        }
    }

    public static function generate(Language $language, VerbStructure $structure): self
    {
        return new VerbParadigm(array_merge(['language_id' => $language->id], $structure->toArray()));
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

    public function getLanguageAttribute(): Language
    {
        if (isset($this->_language)) {
            return $this->_language;
        }

        $this->_language = Language::find($this->language_id);
        return $this->_language;
    }

    public function getFormsAttribute(): Collection
    {
        if (isset($this->_forms)) {
            return $this->_forms;
        }

        $query = [
            'languages' => [$this->language_id],
            'modes' => [$this->mode_name],
            'orders' => [$this->order_name],
            'classes' => [$this->class_abv],
            'negative' => (bool)$this->is_negative,
            'diminutive' => (bool)$this->is_diminutive,
            'subject' => $this->subject_name === '?',
            'primary_object' => $this->primary_object_name === '?',
            'secondary_object' => $this->secondary_object_name === '?',
        ];

        $this->_forms = VerbSearch::search($query);
        return $this->_forms;
    }
}
