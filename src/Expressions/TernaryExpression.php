<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class TernaryExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $if = '';

    /** @var string */
    protected $then = '';

    /** @var string */
    protected $else = '';

    /**
     * Create predicate expression.
     *
     * @param string $if
     * @param string $then
     * @param string $else
     */
    public function __construct($if, $then, $else = null)
    {
        $this->if = $if;
        $this->then = $then;
        $this->else = $else;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        return $this->if->compile($queryBuilder) .
            ' ? ' . $this->then->compile($queryBuilder) .
            ' : ' . $this->else->compile($queryBuilder);
    }
}
