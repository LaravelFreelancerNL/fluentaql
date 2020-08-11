<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class SearchClause extends FilterClause
{
    protected $predicates = [];

    protected $defaultLogicalOperator = 'AND';

    public function compile()
    {
        $compiledPredicates = $this->compilePredicates($this->predicates);

        return 'SEARCH '.rtrim($compiledPredicates);
    }
}
