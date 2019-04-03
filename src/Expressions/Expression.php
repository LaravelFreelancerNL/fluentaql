<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

abstract class Expression
{

    /** @var string |*/
    protected $expression = '';

    /**
     * Create an expression
     *
     * @param string $expression
     */
    function __construct($expression)
    {
        $this->expression = $expression;
    }

    function compile()
    {
        return $this->expression;
    }

    /**
     * Get expression
     *
     * @return string
     */
    function __toString()
    {
        return $this->compile();
    }
}