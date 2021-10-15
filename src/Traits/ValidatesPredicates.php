<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;

trait ValidatesPredicates
{
    public function isPredicate(mixed $value): bool
    {
        if ($value instanceof PredicateExpression) {
            return true;
        }

        if (is_array($value) && isset($value[0]) && ! is_array($value[0])) {
            return true;
        }

        return false;
    }
}
