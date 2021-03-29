<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Array AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-array.html
 */
trait HasArrayFunctions
{
    /**
     * @param mixed $value
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
     * @param mixed $value
     *
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
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function first($value): FunctionExpression
    {
        return new FunctionExpression('FIRST', [$value]);
    }

    /**
     * Get the last element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#last
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function last($value): FunctionExpression
    {
        return new FunctionExpression('LAST', [$value]);
    }

    /**
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#length
     * @link https://www.arangodb.com/docs/3.6/aql/functions-string.html#length
     *
     * @param mixed$value
     *
     * @return FunctionExpression
     */
    public function length($value): FunctionExpression
    {
        return new FunctionExpression('LENGTH', [$value]);
    }
}
