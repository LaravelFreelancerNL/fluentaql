<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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
     * @param array<mixed>|string|QueryBuilder|Expression $value
     */
    public function average(array|string|QueryBuilder|Expression $value): FunctionExpression
    {
        return new FunctionExpression('AVERAGE', [$value]);
    }

    /**
     * @param array<mixed>|string|QueryBuilder|Expression $value
     */
    public function avg(array|string|QueryBuilder|Expression $value): FunctionExpression
    {
        return $this->average($value);
    }

    /**
     * Return the integer closest but not less than value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#ceil
     */
    public function ceil(
        int|float|QueryBuilder|Expression $value
    ): FunctionExpression {
        return new FunctionExpression('CEIL', [$value]);
    }

    /**
     * Return the integer closest but not greater than value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#floor
     */
    public function floor(
        int|float|QueryBuilder|Expression $value
    ): FunctionExpression {
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
     *
     * @param array<mixed>|object $array
     */
    public function product(
        array|object $array
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
        int|float|object $start,
        int|float|object $stop,
        int|float|object $step
    ): FunctionExpression {
        return new FunctionExpression('RANGE', [$start, $stop, $step]);
    }

    /**
     * Return the integer closest to value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#round
     */
    public function round(int|float|QueryBuilder|Expression $value): FunctionExpression
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
