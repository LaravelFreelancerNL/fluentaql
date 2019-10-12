<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * List expression
 */
class ListExpression extends Expression implements ExpressionInterface
{
    public function compile()
    {
        return json_encode($this->expression, JSON_UNESCAPED_SLASHES);
    }
}
