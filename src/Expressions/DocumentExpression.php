<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * Key expression
 */
class DocumentExpression extends Expression implements ExpressionInterface
{
    public function compile()
    {
        return json_encode($this->expression, JSON_UNESCAPED_SLASHES);
    }
}
