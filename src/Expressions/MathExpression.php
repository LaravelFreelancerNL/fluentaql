<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

class MathExpression extends PredicateExpression implements ExpressionInterface
{
    /** @var string */
    protected $leftOperand = '';

    /** @var string */
    protected $rightOperand = '';

    /** @var string */
    protected $operator = '';

    /**
     * Create predicate expression.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $operator
     */
    public function __construct($leftOperand, $rightOperand, $operator = '+')
    {
        $this->leftOperand = $leftOperand;
        $this->rightOperand = $rightOperand;
        $this->operator = $operator;
    }
}
