<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PredicateExpression extends Expression implements ExpressionInterface
{
    protected string|ExpressionInterface $leftOperand;

    protected string|null $comparisonOperator;

    protected string|ExpressionInterface|null $rightOperand;

    public string $logicalOperator;

    /**
     * Create predicate expression.
     *
     * @param ExpressionInterface $leftOperand
     * @param ?string $comparisonOperator
     * @param ?ExpressionInterface $rightOperand
     * @param string|null $logicalOperator
     */
    public function __construct(
        ExpressionInterface $leftOperand,
        ?string $comparisonOperator = null,
        ?ExpressionInterface $rightOperand = null,
        ?string $logicalOperator = 'AND'
    ) {
        $this->leftOperand = $leftOperand;
        $this->comparisonOperator = strtoupper($comparisonOperator);
        $this->rightOperand = $rightOperand;
        $this->logicalOperator = strtoupper($logicalOperator);
    }

    /**
     * Compile predicate string.
     *
     * @param  QueryBuilder|null  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        $compiledPredicate = $this->leftOperand->compile($queryBuilder);
        if (isset($this->comparisonOperator) && $this->comparisonOperator !== '') {
            $compiledPredicate .= ' ' . $this->comparisonOperator;

            if (! isset($this->rightOperand)) {
                $this->rightOperand = new NullExpression();
            }
            $compiledPredicate .= ' ' . $this->rightOperand->compile($queryBuilder);
        }
        return $compiledPredicate;
    }
}
