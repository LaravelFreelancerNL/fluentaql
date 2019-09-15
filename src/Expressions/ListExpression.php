<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL List expression
 */
class ListExpression extends Expression implements ExpressionInterface
{
    public function compile()
    {
        if (is_object($this->expression)) {
            return json_encode($this->expression, JSON_UNESCAPED_SLASHES);
        }

        return '['.implode(', ', $this->expression).']';
    }
}
