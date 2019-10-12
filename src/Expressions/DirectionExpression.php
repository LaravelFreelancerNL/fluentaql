<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression
 */
class DirectionExpression extends Expression implements ExpressionInterface
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
