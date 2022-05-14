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
     * Return the cosine similarity between x and y.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#cosine_similarity
     *
     * @param array<array<int|float>|int|float>|QueryBuilder|Expression $x
     * @param array<array<int|float>|int|float>|QueryBuilder|Expression $y
     */
    public function cosineSimilarity(
        array|QueryBuilder|Expression $x,
        array|QueryBuilder|Expression $y
    ): FunctionExpression {
        return new FunctionExpression('COSINE_SIMILARITY', [$x, $y]);
    }

    /**
     * Calculate the score for one or multiple values with a Gaussian function that decays
     * depending on the distance of a numeric value from a user-given origin.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#decay_gauss
     *
     * @param array<int|float>|int|float $value
     */
    public function decayGauss(
        array|int|float|QueryBuilder|Expression $value,
        int|float|QueryBuilder|Expression $origin,
        int|float|QueryBuilder|Expression $scale,
        int|float|QueryBuilder|Expression $offset,
        int|float|QueryBuilder|Expression $decay,
    ): FunctionExpression {
        return new FunctionExpression('DECAY_GAUSS', [$value, $origin, $scale, $offset, $decay]);
    }

    /**
     * Calculate the score for one or multiple values with an exponential function that decays depending
     * on the distance of a numeric value from a user-given origin.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#decay_exp
     */
    public function decayExp(
        int|float|QueryBuilder|Expression $value,
        int|float|QueryBuilder|Expression $origin,
        int|float|QueryBuilder|Expression $scale,
        int|float|QueryBuilder|Expression $offset,
        int|float|QueryBuilder|Expression $decay,
    ): FunctionExpression {
        return new FunctionExpression('DECAY_EXP', [$value, $origin, $scale, $offset, $decay]);
    }

    /**
     * Calculate the score for one or multiple values with a linear function that decays depending
     * on the distance of a numeric value from a user-given origin.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#decay_linear
     */
    public function decayLinear(
        int|float|QueryBuilder|Expression $value,
        int|float|QueryBuilder|Expression $origin,
        int|float|QueryBuilder|Expression $scale,
        int|float|QueryBuilder|Expression $offset,
        int|float|QueryBuilder|Expression $decay,
    ): FunctionExpression {
        return new FunctionExpression('DECAY_LINEAR', [$value, $origin, $scale, $offset, $decay]);
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
     * Return the Manhattan distance between x and y.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#l1_distance
     *
     * @param array<array<int|float>|int|float>|QueryBuilder|Expression $x
     * @param array<array<int|float>|int|float>|QueryBuilder|Expression $y
     */
    public function l1Distance(
        array|QueryBuilder|Expression $x,
        array|QueryBuilder|Expression $y
    ): FunctionExpression {
        return new FunctionExpression('L1_DISTANCE', [$x, $y]);
    }

    /**
     * Return the Euclidean distance between x and y.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#l2_distance
     *
     * @param array<array<int|float>|int|float>|QueryBuilder|Expression $x
     * @param array<array<int|float>|int|float>|QueryBuilder|Expression $y
     */
    public function l2Distance(
        array|QueryBuilder|Expression $x,
        array|QueryBuilder|Expression $y
    ): FunctionExpression {
        return new FunctionExpression('L2_DISTANCE', [$x, $y]);
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
