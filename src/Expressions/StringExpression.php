<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * String expression
 */
class StringExpression extends Expression implements ExpressionInterface
{
    /**
     * Compile expression output
     *
     * @return string
     */
    public function compile()
    {
        return '"'.$this->expression.'"';
    }
}
