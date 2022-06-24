<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * List expression.
 */
class ListExpression extends Expression implements ExpressionInterface
{
    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     *
     * @param  array<array-key, null|object|scalar>|Expression  $expression
     */
    public function __construct(
        array|Expression $expression
    ) {
        $this->expression = $expression;
    }

    /**
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        /** @var array<array-key, Expression> $expressions */
        $expressions = [];
        /**
         * @var array-key $key
         * @var mixed $value
         */
        foreach ($this->expression as $key => $value) {
            $expressions[$key] = $queryBuilder->normalizeArgument($value);
        }

        $outputStrings = [];
        foreach ($expressions as $expressionElement) {
            $outputStrings[] = $expressionElement->compile($queryBuilder);
        }

        return '['.implode(',', $outputStrings).']';
    }
}
