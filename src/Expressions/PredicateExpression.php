<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

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
     * Create predicate expression
     *
     * @param ExpressionInterface $leftOperand
     * @param string $comparisonOperator
     * @param ExpressionInterface $rightOperand
     * @param null|string $logicalOperator
     */
    public function __construct(ExpressionInterface $leftOperand, $comparisonOperator = '==', ExpressionInterface $rightOperand = null, $logicalOperator = null)
    {
        $this->leftOperand = $leftOperand;
        $this->comparisonOperator = $comparisonOperator;
        $this->rightOperand = $rightOperand;
        $this->logicalOperator = $logicalOperator;
    }

    /**
     * Compile predicate string
     *
     * @return string
     */
    public function compile()
    {
        return $this->leftOperand.' '.$this->comparisonOperator.' '.$this->rightOperand;
    }
}
