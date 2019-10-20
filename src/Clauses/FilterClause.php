<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;

class FilterClause extends Clause
{
    protected $predicates = [];

    protected $defaultLogicalOperator = 'AND';

    /**
     * Filter statement.
     *
     * @param  array $predicates
     */
    public function __construct($predicates)
    {
        parent::__construct();

        $this->predicates = $predicates;
    }

    public function compile()
    {
        $compiledPredicates = $this->compilePredicates($this->predicates);
        return 'FILTER '.rtrim($compiledPredicates);
    }

    protected function compilePredicates($predicates, $compiledPredicates = '')
    {
        $currentLogicalOperator = $this->defaultLogicalOperator;
        foreach ($predicates as $predicate) {
            if ($predicate instanceof PredicateExpression) {
                if ($compiledPredicates != '' && $compiledPredicates != '(') {
                    $compiledPredicates .= ' '.$currentLogicalOperator.' ';
                }
                $compiledPredicates .= $predicate;
            }

            if (is_array($predicate)) {
                $currentLogicalOperator = $this->defaultLogicalOperator;
                if (isset($predicate['logicalOperator'])) {
                    $currentLogicalOperator = $predicate['logicalOperator'];
                }
                $compiledPredicates = $this->compilePredicates($predicate, $compiledPredicates);
            }
        }

        return $compiledPredicates;
    }
}
