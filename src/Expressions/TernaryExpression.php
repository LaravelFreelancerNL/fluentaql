<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class TernaryExpression extends Expression implements ExpressionInterface
{
    protected mixed $predicates = [];

    protected mixed $then = '';

    protected mixed $else = '';

    public function __construct(
        mixed $predicates,
        mixed $then,
        mixed $else = null
    ) {
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
