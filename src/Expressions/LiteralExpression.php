<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression.
 */
class LiteralExpression extends Expression implements ExpressionInterface
{
    /**
     * Compile expression output.
     *
     * @return string
     */
    public function compile()
    {
        return (string) $this->expression;
    }
}
