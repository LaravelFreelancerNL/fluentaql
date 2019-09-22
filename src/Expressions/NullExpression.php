<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression
 */
class NullExpression extends Expression implements ExpressionInterface
{
    /**
     * Compile expression output
     *
     * @return string
     */
    public function compile()
    {
        return 'null';
    }
}
