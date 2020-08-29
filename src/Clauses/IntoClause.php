<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class IntoClause extends Clause
{

    protected $groupsVariable;

    protected $projectionExpression;

    public function __construct($groupsVariable, $projectionExpression = null)
    {
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

        $output = 'INTO ' . $this->groupsVariable->compile($queryBuilder);
        if (isset($this->projectionExpression)) {
            $output .= ' = ' . $this->projectionExpression->compile($queryBuilder);
        }

        return $output;
    }
}
