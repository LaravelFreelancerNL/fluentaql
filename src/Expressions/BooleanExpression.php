<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression.
 */
class BooleanExpression extends LiteralExpression implements ExpressionInterface
{
    /**
     * Compile expression output.
     *
     * @return string
     */
    public function compile()
    {
        return ($this->expression) ? 'true' : 'false';
    }
}
