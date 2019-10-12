<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class PruneClause extends FilterClause
{
    protected $predicates = [];

    protected $defaultLogicalOperator = 'AND';

    public function compile()
    {
        $compiledPredicates = $this->compilePredicates($this->predicates);
        return 'PRUNE '.rtrim($compiledPredicates);
    }
}
