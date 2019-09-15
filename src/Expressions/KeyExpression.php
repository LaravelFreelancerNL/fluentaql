<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL List expression
 */
class KeyExpression extends Expression implements ExpressionInterface
{
    public function compile()
    {
        return '"'.$this->expression.'"';
    }
}
