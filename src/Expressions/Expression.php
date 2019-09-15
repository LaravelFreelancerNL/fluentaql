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
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Compile expression output
     *
     * @return string
     */
    public function compile()
    {
        return (string) $this->expression;
    }

    /**
     * Get expression
     *
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }
}
