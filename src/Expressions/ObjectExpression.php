<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Key expression.
 */
class ObjectExpression extends Expression implements ExpressionInterface
{
    public function compile(QueryBuilder $queryBuilder): string
    {
        $output = '';
        foreach ($this->expression as $key => $value) {
            if ($output != '') {
                $output .= ',';
            }
            $output .= '"' . $key . '":' . $value->compile($queryBuilder);
        }

        return '{' . $output . '}';
    }
}
