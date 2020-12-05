<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Geo AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-string.html
 */
trait HasStringFunctions
{
    /**
     * Concatenate the values passed as value1 to valueN..
     *
     * https://www.arangodb.com/docs/stable/aql/functions-string.html#concat
     *
     * @param  array  $arguments
     * @return FunctionExpression
     */
    public function concat(...$arguments)
    {
        return new FunctionExpression('CONCAT', $arguments);
    }
}
