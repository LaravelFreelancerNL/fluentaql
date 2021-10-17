<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait CompilesPredicates
{
    /**
     * @param array<mixed>|PredicateExpression $predicates
     */
    public function compilePredicates(
        array|PredicateExpression $predicates
    ): string {
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

    protected function compilePredicate(PredicateExpression $predicate, int $position = 0): string
    {
        $compiledPredicate = '';
        if ($position > 0) {
            $compiledPredicate = $predicate->logicalOperator . ' ' ;
        }
        return $compiledPredicate . $predicate->compile($this);
    }

    /**
     * @param array<mixed> $predicates
     * @param int|QueryBuilder|Expression $position
     * @return string
     */
    protected function compilePredicateGroup(
        array $predicates,
        int|QueryBuilder|Expression $position = 0
    ): string {
        $compiledPredicates = [];
        $logicalOperator = '';
        if ($predicates[0] instanceof PredicateExpression) {
            $logicalOperator = $predicates[0]->logicalOperator;
        }
        for ($i = 0; $i < count($predicates); $i++) {
            $compiledPredicates[] = $this->compilePredicate($predicates[$i], $i);
        }

        $groupCompilation = '';
        if ((int) $position > 0) {
            $groupCompilation = $logicalOperator . ' ';
        }
        return $groupCompilation . '(' . implode(' ', $compiledPredicates) . ')';
    }
}
