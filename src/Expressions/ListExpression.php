<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * List expression
 */
class ListExpression extends Expression implements ExpressionInterface
{
    /**
     * @param array $expression
     */
    public function __construct(array $expression)
    {
        $this->expression = $expression;
    }

    public function compile()
    {
        return '['.implode(',', $this->expression).']';
    }
}
