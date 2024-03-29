<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;

trait ValidatesPredicates
{
    public function isPredicate(mixed $value): bool
    {
        if ($value instanceof PredicateExpression) {
            return true;
        }

        if (
            is_array($value)
            && isset($value[0])
            && !is_array($value[0])
            && !$value[0] instanceof PredicateExpression
        ) {
            return true;
        }

        return false;
    }
}
