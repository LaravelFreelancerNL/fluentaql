<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PredicateExpression extends Expression implements ExpressionInterface
{
    /**
     * @var object|array<mixed>|string|int|float|bool|null
     */
    protected object|array|string|int|float|bool|null $leftOperand;

    protected ?string $comparisonOperator;

    /**
     * @var object|array<mixed>|string|int|float|bool|null
     */
    protected object|array|string|int|float|bool|null $rightOperand;

    public string $logicalOperator;

    /**
     * Create predicate expression.
     *
     * @param  object|array<mixed>|string|int|float|bool|null  $leftOperand
     * @param  ?string  $comparisonOperator
     * @param  object|array<mixed>|string|int|float|bool|null  $rightOperand
     */
    public function __construct(
        object|array|string|int|float|bool|null $leftOperand,
        string $comparisonOperator = null,
        object|array|string|int|float|bool $rightOperand = null,
        string $logicalOperator = 'AND'
    ) {
        $this->leftOperand = $leftOperand;
        $this->comparisonOperator = strtoupper((string) $comparisonOperator);
        $this->rightOperand = $rightOperand;
        $this->logicalOperator = strtoupper($logicalOperator);
    }

    /**
     * Compile predicate string.
     *
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        $leftOperand = $queryBuilder->normalizeArgument($this->leftOperand);

        $compiledPredicate = $leftOperand->compile($queryBuilder);
        if (isset($this->comparisonOperator) && $this->comparisonOperator !== '') {
            $compiledPredicate .= ' '.$this->comparisonOperator;

            $rightOperand = $queryBuilder->normalizeArgument($this->rightOperand);

            $compiledPredicate .= ' '.$rightOperand->compile($queryBuilder);
        }

        return $compiledPredicate;
    }
}
