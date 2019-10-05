<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression
 */
class NullExpression extends Expression implements ExpressionInterface
{
    /**
     * Create an expression
     *
     * @param mixed $expression
     */
    public function __construct()
    {}


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
