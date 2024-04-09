<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;

trait CompilesPredicates
{
    /**
     * @param  array<mixed>|PredicateExpression  $predicates
     */
    public function compilePredicates(
        array|PredicateExpression $predicates
    ): string {
        if (!is_array($predicates)) {
            $predicates = [$predicates];
        }

        $compiledPredicates = [];
        $count = count($predicates);
        for ($i = 0; $i < $count; $i++) {
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
            $compiledPredicate = $predicate->logicalOperator . ' ';
        }

        return $compiledPredicate . $predicate->compile($this);
    }

    /**
     * @param  array<mixed>  $predicates
     */
    protected function compilePredicateGroup(
        array $predicates,
        int $position = 0
    ): string {
        $compiledPredicates = [];
        $logicalOperator = '';
        if ($predicates[0] instanceof PredicateExpression) {
            $logicalOperator = $predicates[0]->logicalOperator;
        }
        $count = count($predicates);
        for ($i = 0; $i < $count; $i++) {
            if ($predicates[$i] instanceof PredicateExpression) {
                $compiledPredicates[] = $this->compilePredicate($predicates[$i], $i);
            }
        }

        $groupCompilation = '';
        if ($position > 0) {
            $groupCompilation = $logicalOperator . ' ';
        }

        return $groupCompilation . '(' . implode(' ', $compiledPredicates) . ')';
    }
}
