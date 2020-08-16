<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class FilterClause extends Clause
{
    protected $predicates = [];

    protected $defaultLogicalOperator = 'AND';

    /**
     * Filter statement.
     *
     * @param array $predicates
     */
    public function __construct(array $predicates)
    {
        parent::__construct();

        $this->predicates = $predicates;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);

        $compiledPredicates = $this->compilePredicates($queryBuilder, $this->predicates);

        return 'FILTER ' . rtrim($compiledPredicates);
    }

    protected function compilePredicates(QueryBuilder $queryBuilder, $predicates, $compiledPredicates = '')
    {
        $currentLogicalOperator = $this->defaultLogicalOperator;
        foreach ($predicates as $predicate) {
            if ($predicate instanceof PredicateExpression) {
                if ($compiledPredicates != '') {
                    $compiledPredicates .= ' ' . $predicate->logicalOperator . ' ';
                }
                $compiledPredicates .= $predicate->compile($queryBuilder);
            }

            if (is_array($predicate)) {
                $compiledPredicates = $this->compilePredicates($queryBuilder, $predicate, $compiledPredicates);
            }
        }

        return $compiledPredicates;
    }
}
