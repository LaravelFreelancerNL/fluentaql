<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class IntoClause extends Clause
{
    protected string|QueryBuilder|Expression $groupsVariable;

    protected mixed $projectionExpression;

    public function __construct(
        string|QueryBuilder|Expression $groupsVariable,
        mixed $projectionExpression = null
    ) {
        $this->groupsVariable = $groupsVariable;
        $this->projectionExpression = $projectionExpression;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->groupsVariable = $queryBuilder->normalizeArgument($this->groupsVariable, 'Variable');
        $queryBuilder->registerVariable($this->groupsVariable);

        if (isset($this->projectionExpression)) {
            $this->projectionExpression = $queryBuilder->normalizeArgument(
                $this->projectionExpression,
                ['Reference', 'Object', 'Function', 'Query', 'Bind']
            );
        }

        $output = 'INTO '.$this->groupsVariable->compile($queryBuilder);
        if (isset($this->projectionExpression)) {
            $output .= ' = '.$this->projectionExpression->compile($queryBuilder);
        }

        return $output;
    }
}
