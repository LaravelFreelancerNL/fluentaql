<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PruneClause extends FilterClause
{
    protected ?string $pruneVariable = null;

    /**
     * Filter statement.
     *
     * @param  array<mixed>  $predicates
     */
    public function __construct(
        array|PredicateExpression $predicates,
        string $pruneVariable = null
    ) {
        parent::__construct($predicates);

        $this->pruneVariable = $pruneVariable;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $aql = 'PRUNE ';

        if (isset($this->pruneVariable)) {
            $pruneVariable = $queryBuilder->normalizeArgument($this->pruneVariable, 'Variable');
            $aql .= $pruneVariable->compile($queryBuilder) . ' = ';
        }

        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);

        $compiledPredicates = $queryBuilder->compilePredicates($this->predicates);

        return  $aql . rtrim($compiledPredicates);
    }
}
