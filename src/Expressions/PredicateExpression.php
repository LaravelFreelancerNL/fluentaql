<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

class PredicateExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $leftOperand = '';

    /** @var string */
    protected $rightOperand = '';

    /** @var string */
    protected $operator = '';

    /**
     * Create predicate expression
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $operator
     */
    function __construct($leftOperand, $rightOperand, $operator = '==')
    {
        $this->leftOperand = $leftOperand;
        $this->rightOperand= $rightOperand;
        $this->operator = $operator;
    }

    /**
     * Compile predicate string
     *
     * @return string
     */
    function compile()
    {
        return $this->leftOperand.' '.$this->operator.' '.$this->rightOperand;
    }

}