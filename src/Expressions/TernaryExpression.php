<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class TernaryExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $predicates = [];

    /** @var string */
    protected $then = '';

    /** @var string */
    protected $else = '';

    /**
     * Create predicate expression.
     *
     * @param string $predicates
     * @param string $then
     * @param string $else
     */
    public function __construct($predicates, $then, $else = null)
    {
        $this->predicates = $predicates;
        $this->then = $then;
        $this->else = $else;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);
        $this->then = $queryBuilder->normalizeArgument($this->then);
        $this->else = $queryBuilder->normalizeArgument($this->else);

        return '(' . $queryBuilder->compilePredicates($this->predicates) . ')' .
            ' ? ' . $this->then->compile($queryBuilder) .
            ' : ' . $this->else->compile($queryBuilder);
    }
}
