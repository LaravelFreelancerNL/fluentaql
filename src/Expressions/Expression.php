<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryElement;

abstract class Expression extends QueryElement
{
    /**
     * @var mixed
     */
    protected $expression;

    /**
     * Create an expression.
     */
    public function __construct(mixed $expression)
    {
        $this->expression = $expression;
    }
}
