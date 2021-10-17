<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\ArithmeticExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\TernaryExpression;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait HasOperatorExpressions
{
    /**
     * Evaluate a condition
     *
     * @link https://www.arangodb.com/docs/stable/aql/operators.html#ternary-operator
     */
    public function if(
        mixed $conditions,
        mixed $then,
        mixed $else
    ): TernaryExpression {
        return new TernaryExpression($conditions, $then, $else);
    }

    /**
     * Perform an arithmetic operation on two numbers
     *
     * @link https://www.arangodb.com/docs/stable/aql/operators.html#arithmetic-operators
     */
    public function calc(
        mixed $leftOperand,
        string $operator,
        mixed $rightOperand
    ): ArithmeticExpression {
        return new ArithmeticExpression($leftOperand, $operator, $rightOperand);
    }
}
