<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Trait hasNumericFunctions.
 *
 * Numeric AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html
 */
trait HasNumericFunctions
{
    /**
     * Return the average (arithmetic mean) of the values in array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#average
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function average($value)
    {
        return new FunctionExpression('AVERAGE', [$value]);
    }

    public function avg($value)
    {
        return $this->average($value);
    }

    /**
     * Return the largest element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#max
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function max($value)
    {
        return new FunctionExpression('MAX', [$value]);
    }

    /**
     * Return the smallest element of an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#min
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function min($value)
    {
        return new FunctionExpression('MIN', [$value]);
    }

    /**
     * Return a pseudo-random number between 0 and 1.
     * https://www.arangodb.com/docs/stable/aql/functions-numeric.html#rand.
     *
     * @return FunctionExpression
     */
    public function rand()
    {
        return new FunctionExpression('RAND');
    }

    /**
     * Return the sum of the values in an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#sum
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function sum($value)
    {
        return new FunctionExpression('SUM', [$value]);
    }
}
