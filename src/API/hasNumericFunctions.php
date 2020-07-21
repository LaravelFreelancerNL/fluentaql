<?php

namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Trait hasNumericFunctions.
 *
 * Numeric AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html
 */
trait hasNumericFunctions
{
    /**
     * Return the average (arithmetic mean) of the values in array.
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#average
     *
     * @param string|array $value
     * @return FunctionExpression
     */
    public function average($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Variable', 'Reference']);

        return new FunctionExpression('AVERAGE', $arguments);
    }

    public function avg($value)
    {
        return $this->average($value);
    }

    /**
     * Return the largest element of an array.
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#max
     *
     * @param string|array $value
     * @return FunctionExpression
     */
    public function max($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Variable', 'Reference']);

        return new FunctionExpression('MAX', $arguments);
    }

    /**
     * Return the smallest element of an array.
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#min
     *
     * @param string|array $value
     * @return FunctionExpression
     */
    public function min($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Variable', 'Reference']);

        return new FunctionExpression('MIN', $arguments);
    }

    /**
     * Return a pseudo-random number between 0 and 1.
     * https://www.arangodb.com/docs/stable/aql/functions-numeric.html#rand.
     *
     * @return FunctionExpression
     */
    public function rand()
    {
        return new FunctionExpression('RAND', null);
    }

    /**
     * Return the sum of the values in an array.
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#sum
     *
     * @param string|array $value
     * @return FunctionExpression
     */
    public function sum($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'Variable', 'Reference']);

        return new FunctionExpression('SUM', $arguments);
    }
}
