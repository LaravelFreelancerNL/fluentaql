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
     *
     * @param $conditions
     * @param $then
     * @param $else
     * @return TernaryExpression
     */
    public function if($conditions, $then, $else)
    {
        return new TernaryExpression($conditions, $then, $else);
    }

    /**
     * Perform an arithmetic operation on two numbers
     *
     * @link https://www.arangodb.com/docs/stable/aql/operators.html#arithmetic-operators
     *
     * @param $leftOperand
     * @param $operator
     * @param $rightOperand
     * @return ArithmeticExpression
     */
    public function calc($leftOperand, $operator, $rightOperand)
    {
        return new ArithmeticExpression($leftOperand, $operator, $rightOperand);
    }
}
