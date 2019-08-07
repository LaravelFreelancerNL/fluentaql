<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL List expression
 */
class ListExpression extends Expression implements ExpressionInterface
{
    function compile(QueryBuilder $qb)
    {
        if (is_object($this->expression)) {
            $this->expression = (array) $this->expression;
        }

        return json_encode($this->expression);
    }
}
