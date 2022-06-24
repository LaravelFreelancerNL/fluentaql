<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Key expression.
 */
class ObjectExpression extends Expression implements ExpressionInterface
{
    /**
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        $output = '';
        /**
         * @var string $key
         * @var object|array<mixed>|scalar $value
         */
        foreach ($this->expression as $key => $value) {
            $value = $queryBuilder->normalizeArgument($value);
            if ($output != '') {
                $output .= ',';
            }
            $output .= '"'.$key.'":'.$value->compile($queryBuilder);
        }

        return '{'.$output.'}';
    }
}
