<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PredicateExpression extends Expression implements ExpressionInterface
{
    protected Expression $leftOperand;

    protected string|null $comparisonOperator;

    protected Expression|null $rightOperand;

    public string $logicalOperator;

    /**
     * Create predicate expression.
     *
     * @param Expression $leftOperand
     * @param ?string $comparisonOperator
     * @param ?Expression $rightOperand
     * @param string|null $logicalOperator
     */
    public function __construct(
        Expression $leftOperand,
        ?string $comparisonOperator = null,
        ?Expression $rightOperand = null,
        ?string $logicalOperator = 'AND'
    ) {
        $this->leftOperand = $leftOperand;
        $this->comparisonOperator = strtoupper((string) $comparisonOperator);
        $this->rightOperand = $rightOperand;
        $this->logicalOperator = strtoupper((string) $logicalOperator);
    }

    /**
     * Compile predicate string.
     *
     * @param  QueryBuilder|null  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        /* @phpstan-ignore-next-line */
        $compiledPredicate = $this->leftOperand->compile($queryBuilder);
        if (isset($this->comparisonOperator) && $this->comparisonOperator !== '') {
            $compiledPredicate .= ' ' . $this->comparisonOperator;

            if (! isset($this->rightOperand)) {
                $this->rightOperand = new NullExpression();
            }
            /* @phpstan-ignore-next-line */
            $compiledPredicate .= ' ' . $this->rightOperand->compile($queryBuilder);
        }
        return $compiledPredicate;
    }
}
