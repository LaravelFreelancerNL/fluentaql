<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL List expression
 */
class ListExpression extends Expression
{

    /** @var string */
    protected $expression;

    /**
     * Create literal expression
     *
     * @param string $expression
     */
    function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Get literal expression
     *
     * @return string
     */
    function __toString()
    {
        return json_encode($this->expression);
    }

}
