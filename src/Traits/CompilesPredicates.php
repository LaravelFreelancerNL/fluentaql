<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait CompilesPredicates
{
    /**
     * @param PredicateExpression|array $predicates
     * @return string
     */
    public function compilePredicates($predicates)
    {
        if (! is_array($predicates)) {
            $predicates = [$predicates];
        }

        $compiledPredicates = [];
        for ($i = 0; $i < count($predicates); $i++) {
            if ($predicates[$i] instanceof PredicateExpression) {
                $compiledPredicates[] = $this->compilePredicate($predicates[$i], $i);
            }

            if (is_array($predicates[$i])) {
                $compiledPredicates[] = $this->compilePredicateGroup($predicates[$i], $i);
            }
        }

        return implode(' ', $compiledPredicates);
    }

    protected function compilePredicate(PredicateExpression $predicate, $position = 0)
    {
        $compiledPredicate = '';
        if ($position > 0) {
            $compiledPredicate = $predicate->logicalOperator . ' ' ;
        }
        return $compiledPredicate . $predicate->compile($this);
    }

    protected function compilePredicateGroup(array $predicates, $position = 0)
    {
        $compiledPredicates = [];
        $logicalOperator = '';
        if ($predicates[0] instanceof PredicateExpression) {
            $logicalOperator = $predicates[0]->logicalOperator;
        }
        for ($i = 0; $i < count($predicates); $i++) {
            $compiledPredicates[] = $this->compilePredicate($predicates[$i], $i);
        }

        $groupCompilation = '';
        if ($position > 0) {
            $groupCompilation = $logicalOperator . ' ';
        }
        return $groupCompilation . '(' . implode(' ', $compiledPredicates) . ')';
    }
}
