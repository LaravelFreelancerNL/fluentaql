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
    public function count($value)
    {
        return $this->length($value);
    }

    /**
     * Get the number of unique elements.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#count_distinct
     *
     * @param $value
     *
     * @return FunctionExpression
     */
    public function countDistinct($value)
    {
        $arguments = [];

        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Reference']);

        return new FunctionExpression('COUNT_DISTINCT', $arguments);
    }

    /**
     * Get the first element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#first
     *
     * @param $value
     *
     * @return FunctionExpression
     */
    public function first($value)
    {
        $arguments = [];

        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Reference']);

        return new FunctionExpression('FIRST', $arguments);
    }

    /**
     * Get the last element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#last
     *
     * @param $value
     *
     * @return FunctionExpression
     */
    public function last($value)
    {
        $arguments = [];

        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Reference']);

        return new FunctionExpression('LAST', $arguments);
    }

    /**
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#length
     *
     * @param $value
     *
     * @return FunctionExpression
     */
    public function length($value)
    {
        $arguments = [];

        $arguments['value'] = $this->normalizeArgument($value);

        return new FunctionExpression('LENGTH', $arguments);
    }
}
