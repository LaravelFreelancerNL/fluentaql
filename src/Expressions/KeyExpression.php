<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL List expression
 */
class KeyExpression extends Expression implements ExpressionInterface
{

    function compile(QueryBuilder $qb)
    {
        return '"'.$this->expression.'"';
    }
}
