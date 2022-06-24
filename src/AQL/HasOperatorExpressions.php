<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\ArithmeticExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\TernaryExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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
     * @param  array<mixed>|PredicateExpression  $conditions
     */
    public function if(
        array|PredicateExpression $conditions,
        mixed $then,
        mixed $else = null
    ): TernaryExpression {
        return new TernaryExpression($conditions, $then, $else);
    }

    /**
     * Perform an arithmetic operation on two numbers
     *
     * @link https://www.arangodb.com/docs/stable/aql/operators.html#arithmetic-operators
     */
    public function calc(
        int|float|null|Expression|QueryBuilder $leftOperand,
        string $operator,
        int|float|null|Expression|QueryBuilder $rightOperand
    ): ArithmeticExpression {
        return new ArithmeticExpression($leftOperand, $operator, $rightOperand);
    }
}
