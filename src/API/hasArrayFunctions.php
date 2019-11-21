<?php

namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Array AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-array.html
 */
trait hasArrayFunctions
{
    public function count($value)
    {
        return $this->length($value);
    }

    /**
     * Get the number of unique elements.
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#count_distinct
     *
     * @param $value
     * @return FunctionExpression
     */
    public function countDistinct($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'VariableAttribute']);

        return new FunctionExpression('COUNT_DISTINCT', $arguments);
    }

    /**
     * Get the first element of an array.
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#first
     *
     * @param $value
     * @return FunctionExpression
     */
    public function first($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'VariableAttribute']);

        return new FunctionExpression('FIRST', $arguments);
    }

    /**
     * Get the last element of an array.
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#last
     *
     * @param $value
     * @return FunctionExpression
     */
    public function last($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'VariableAttribute']);

        return new FunctionExpression('LAST', $arguments);
    }

    /**
     * @link https://www.arangodb.com/docs/stable/aql/functions-array.html#length
     *
     * @param $value
     * @return FunctionExpression
     */
    public function length($value)
    {
        $arguments['value'] = $this->normalizeArgument($value);

        return new FunctionExpression('LENGTH', $arguments);
    }
}
