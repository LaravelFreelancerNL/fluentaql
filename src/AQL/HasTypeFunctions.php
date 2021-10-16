<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Type AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-type-cast.html
 */
trait HasTypeFunctions
{
    /**
     * Take an input value of any type and convert it into an array value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_array
     */
    public function toArray(
        mixed $value,
    ): FunctionExpression {
        return new FunctionExpression('TO_ARRAY', [$value]);
    }

    /**
     * Take an input value of any type and convert it into the appropriate boolean value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_bool
     */
    public function toBool(
        mixed $value,
    ): FunctionExpression {
        return new FunctionExpression('TO_BOOL', [$value]);
    }

    /**
     * Alias for toArray
     */
    public function toList(
        mixed $value,
    ): FunctionExpression {
        return $this->toArray($value);
    }

    /**
     * Take an input value of any type and convert it into a numeric value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_number
     */
    public function toNumber(
        mixed $value,
    ): FunctionExpression {
        return new FunctionExpression('TO_NUMBER', [$value]);
    }

    /**
     * Take an input value of any type and convert it into a string value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_string
     */
    public function toString(
        mixed $value,
    ): FunctionExpression {
        return new FunctionExpression('TO_STRING', [$value]);
    }
}
