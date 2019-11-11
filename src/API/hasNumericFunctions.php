<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;

/**
 * Trait hasNumericFunctions
 *
 * Numeric AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasNumericFunctions
{

    /**
     * Return one or more specific documents from a collection.
     * @link https://www.arangodb.com/docs/stable/aql/functions-numeric.html#max
     *
     * @param string|array $value
     * @return FunctionExpression
     */
    public function max($value)
    {
        $arguments['value'] = $this->normalizeArgument($value, ['List', 'VariableAttribute']);

        return new FunctionExpression('MAX', $arguments);
    }
}
