<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class AggregateClause extends Clause
{
    protected string|QueryBuilder|Expression $variableName;
    protected string|QueryBuilder|Expression $aggregateExpression;

    public function __construct(
        string|QueryBuilder|Expression $variableName,
        string|QueryBuilder|Expression $aggregateExpression
    ) {
        $this->variableName = $variableName;

        $this->aggregateExpression = $aggregateExpression;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->variableName = $queryBuilder->normalizeArgument($this->variableName, 'Variable');
        $queryBuilder->registerVariable($this->variableName);

        $this->aggregateExpression = $queryBuilder->normalizeArgument(
            $this->aggregateExpression,
            ['Reference', 'Function', 'Query', 'Bind']
        );

        return "AGGREGATE {$this->variableName->compile($queryBuilder)} " .
            "= {$this->aggregateExpression->compile($queryBuilder)}";
    }
}
