<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Key expression.
 */
class ObjectExpression extends Expression implements ExpressionInterface
{
    public function compile(QueryBuilder $queryBuilder)
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
