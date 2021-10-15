<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryElement;

abstract class Expression extends QueryElement
{
    protected $expression;

    /**
     * Create an expression.
     *
     * @param mixed $expression
     */
    public function __construct(mixed $expression)
    {
        $this->expression = $expression;
    }
}
