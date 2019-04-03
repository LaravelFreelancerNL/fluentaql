<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL List expression
 */
class ListExpression extends Expression implements ExpressionInterface
{
    function compile()
    {
        if (is_object($this->expression)) {
            $this->expression = (array) $this->expression;
        }

        return json_encode($this->expression);
    }
}
