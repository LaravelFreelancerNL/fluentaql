<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PredicateExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $leftOperand;

    /** @var string */
    protected $comparisonOperator;

    /** @var string */
    protected $rightOperand;

    /** @var string */
    public $logicalOperator;

    /**
     * Create predicate expression.
     *
     * @param ExpressionInterface $leftOperand
     * @param string              $comparisonOperator
     * @param ExpressionInterface $rightOperand
     * @param null|string         $logicalOperator
     */
    public function __construct(
        ExpressionInterface $leftOperand,
        $comparisonOperator,
        ExpressionInterface $rightOperand,
        $logicalOperator = 'AND'
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
        return $this->leftOperand->compile($queryBuilder) .
            ' ' . $this->comparisonOperator . ' ' . $this->rightOperand->compile($queryBuilder);
    }
}
