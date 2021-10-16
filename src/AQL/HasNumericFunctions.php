<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use MongoDB\Driver\Query;

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
     * Return the integer closest but not less than value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#ceil
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function ceil(int|float|QueryBuilder|Expression $value)
    {
        return new FunctionExpression('CEIL', [$value]);
    }

    /**
     * Return the integer closest but not greater than value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#floor
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function floor(int|float|QueryBuilder|Expression $value)
    {
        return new FunctionExpression('FLOOR', [$value]);
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
     * Return the product of the values in array
     *
     * https://www.arangodb.com/docs/stable/aql/functions-numeric.html#product
     */
    public function product(
        array|QueryBuilder|Expression $array
    ): FunctionExpression {
        return new FunctionExpression('PRODUCT', [$array]);
    }

    /**
     * Return a pseudo-random number between 0 and 1.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-numeric.html#rand.
     *
     * @return FunctionExpression
     */
    public function rand()
    {
        return new FunctionExpression('RAND');
    }

    /**
     * Return an array of numbers in the specified range, optionally with increments other than 1.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#range
     */
    public function range(
        int|float|QueryBuilder|Expression $start,
        int|float|QueryBuilder|Expression $stop,
        int|float|QueryBuilder|Expression $step
    ): FunctionExpression {
        return new FunctionExpression('RANGE', [$start, $stop, $step]);
    }

    /**
     * Return the integer closest to value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#round
     *
     * @param mixed $value
     *
     * @return FunctionExpression
     */
    public function round(int|float|QueryBuilder|Expression $value)
    {
        return new FunctionExpression('ROUND', [$value]);
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
