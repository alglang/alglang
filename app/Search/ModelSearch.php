<?php

namespace App\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Dedicated class for running complex queries on forms
 */
class ModelSearch
{
    /** @var Builder */
    protected $query;

    /** @var array */
    protected $filters;

    protected function __construct(Builder $query, array $filters)
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    /**
     * Constrains the underlying query based on provided parameters
     *
     * @param Collection $params The names and values for the constraints to
     *                           apply to the query
     *
     * @return self
     */
    public function constrain(Collection $params): self
    {
        $params->each(function ($value, $key) {
            $filter = $this->filters[$key];
            $filter($value);
        });

        return $this;
    }

    /**
     * Executes the query against the database and returns the results
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * Adds a clause to the query that $column should be one of the provided
     * $options
     *
     * @param string $column  The column to constrain
     * @param array  $options The options for values of the column
     *
     * @return self
     */
    protected function whereIn(string $column, array $options): self
    {
        $this->query->whereIn($column, $options);
        return $this;
    }

    /**
     * Adds a clause to the query that a given relation should have a column
     * that equals a given value
     *
     * @param string $relation  The relation the constraint should apply to
     * @param string $column    The column on the related model to constrain
     * @param mixed  $value     The value the column should have
     *
     * @return self
     */
    protected function whereRelation(string $relation, string $column, $value): self
    {
        $this->query->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, $value);
        });
        return $this;
    }

    /**
     * Adds a clause to the query that a given relation should have a column
     * that contains one of a list of options
     *
     * @param string $relation  The relation the constraint should apply to
     * @param string $column    The column on the related model to constrain
     * @param array  $options   The options for values of the column
     *
     * @return self
     */
    protected function whereInRelation(string $relation, string $column, array $options): self
    {
        $this->query->whereHas($relation, function ($query) use ($column, $options) {
            $query->whereIn($column, $options);
        });
        return $this;
    }

    /**
     * Adds a clause to the query that the model should not have a relation
     *
     * @param string $relation  The relation the model should not have
     *
     * @return self
     */
    protected function whereNoRelation(string $relation): self
    {
        $this->query->whereDoesntHave($relation);
        return $this;
    }
}
