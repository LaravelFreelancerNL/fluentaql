<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Array AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-array.html
 */
trait HasArrayFunctions
{
    /**
     * Add all elements of an array to another array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#append
     *
     * @param  array<mixed>|object  $array
     * @param  mixed  $values
     * @param  bool|null  $unique
     * @return FunctionExpression
     */
    public function append(mixed $array, mixed $values, bool $unique = null): FunctionExpression
    {
        $arguments = [
            'array' => $array,
            'values' => $values,
        ];
        if (isset($unique)) {
            $arguments['unique'] = $unique;
        }

        return new FunctionExpression('APPEND', $arguments);
    }

    /**
     * This is an alias for LENGTH().
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#length
     *
     * @param  mixed  $value
     * @return FunctionExpression
     */
    public function count($value): FunctionExpression
    {
        return $this->length($value);
    }

    /**
     * Get the number of unique elements.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#count_distinct
     *
     * @param  mixed  $value
     * @return FunctionExpression
     */
    public function countDistinct($value): FunctionExpression
    {
        return new FunctionExpression('COUNT_DISTINCT', [$value]);
    }

    /**
     * Get the first element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#first
     *
     * @param  mixed  $value
     * @return FunctionExpression
     */
    public function first($value): FunctionExpression
    {
        return new FunctionExpression('FIRST', [$value]);
    }

    /**
     * Turn an array of arrays into a flat array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#flatten
     *
     * @param  array<mixed>|object  $array
     * @param  int|object  $depth
     * @return FunctionExpression
     */
    public function flatten(mixed $array, mixed $depth = 1): FunctionExpression
    {
        $arguments = [
            'array' => $array,
            'depth' => $depth,
        ];

        return new FunctionExpression('FLATTEN', $arguments);
    }

    /**
     * Return the intersection of all arrays specified.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#intersection
     *
     * @param  array<mixed>  ...$arrays
     * @return FunctionExpression
     */
    public function intersection(array ...$arrays): FunctionExpression
    {
        return new FunctionExpression('INTERSECTION', $arrays);
    }

    /**
     * Get the last element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#last
     *
     * @param  mixed  $value
     * @return FunctionExpression
     */
    public function last($value): FunctionExpression
    {
        return new FunctionExpression('LAST', [$value]);
    }

    /**
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#length
     *
     * @param  mixed  $value
     * @return FunctionExpression
     */
    public function length(mixed $value): FunctionExpression
    {
        return new FunctionExpression('LENGTH', [$value]);
    }

    /**
     * Remove the first element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#shift
     *
     * @param  array<mixed>|object  $array
     * @return FunctionExpression
     */
    public function shift(mixed $array): FunctionExpression
    {
        return new FunctionExpression('SHIFT', [$array]);
    }

    /**
     * Return the union of all arrays specified.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#union
     *
     * @param  array<mixed>|Expression|QueryBuilder  $arrays
     * @return FunctionExpression
     */
    public function union(
        array|Expression|QueryBuilder ...$arrays
    ): FunctionExpression {
        return new FunctionExpression('UNION', [$arrays]);
    }

    /**
     * Return the union of distinct values of all arrays specified.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#union_distinct
     *
     * @param  array<mixed>|Expression|QueryBuilder  $arrays
     * @return FunctionExpression
     */
    public function unionDistinct(
        array|Expression|QueryBuilder ...$arrays
    ): FunctionExpression {
        return new FunctionExpression('UNION_DISTINCT', [$arrays]);
    }

    /**
     * Return all unique elements in an Array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#unique
     *
     * @param  array<mixed>|object  $array
     * @return FunctionExpression
     */
    public function unique(mixed $array): FunctionExpression
    {
        return new FunctionExpression('UNIQUE', [$array]);
    }
}
