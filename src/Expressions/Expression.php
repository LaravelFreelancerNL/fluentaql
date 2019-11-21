<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

abstract class Expression
{
    /** @var mixed */
    protected $expression;

    /**
     * Create an expression.
     *
     * @param mixed $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Compile expression output.
     *
     * @return string
     */
    public function compile()
    {
        return (string) $this->expression;
    }

    /**
     * Get expression.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }
}
