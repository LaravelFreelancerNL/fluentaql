<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PredicateExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $expressions = [];

    /** @var string */
    protected $operator = '';

    /**
     * Create predicate expression
     *
     * @param ExpressionInterface $leftOperand
     * @param ExpressionInterface $rightOperand
     * @param string $operator
     */
    public function __construct(ExpressionInterface $leftOperand, ExpressionInterface $rightOperand, $operator = '==')
    {
        $this->expressions['leftOperand'] = $leftOperand;
        $this->expressions['rightOperand'] = $rightOperand;
        $this->operator = $operator;
    }

    /**
     * Compile predicate string
     *
     * @param QueryBuilder $qb
     * @return string
     */
    public function compile()
    {
        return $this->expressions['leftOperand'].' '.$this->operator.' '.$this->expressions['rightOperand'];
    }
}
