<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * List expression.
 */
class ListExpression extends Expression implements ExpressionInterface
{
    /**
     * @param array $expression
     */
    public function __construct(array $expression)
    {
        $this->expression = $expression;
    }

    public function compile(QueryBuilder $queryBuilder = null)
    {
        //normalize $this_expression
        foreach ($this->expression as $key => $value) {
            $this->expression[$key] = $queryBuilder->normalizeArgument($value);
        }

        $outputStrings = [];
        foreach ($this->expression as $expressionElement) {
            $outputStrings[] = $expressionElement->compile($queryBuilder);
        }

        return '[' . implode(',', $outputStrings) . ']';
    }
}
