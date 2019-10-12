<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PredicateExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $leftOperand;

    /** @var string */
    protected $operator = '';

    /** @var string */
    protected $rightOperand;


    /**
     * Create predicate expression
     *
     * @param ExpressionInterface $leftOperand
     * @param string $operator
     * @param ExpressionInterface $rightOperand
     */
    public function __construct(ExpressionInterface $leftOperand, $operator = '==', ExpressionInterface $rightOperand = null)
    {
        $this->leftOperand = $leftOperand;
        $this->operator = $operator;
        $this->rightOperand = $rightOperand;
    }

    /**
     * Compile predicate string
     *
     * @return string
     */
    public function compile()
    {
        return $this->leftOperand.' '.$this->operator.' '.$this->rightOperand;
    }
}
