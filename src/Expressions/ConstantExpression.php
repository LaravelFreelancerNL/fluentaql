<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression
 */
class ConstantExpression extends LiteralExpression implements ExpressionInterface
{
    /**
     * Compile expression output
     *
     * @return string
     */
    public function compile()
    {
        return strtoupper($this->expression);
    }
}
