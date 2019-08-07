<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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

    function compile(QueryBuilder $qb)
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