<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

class ConditionalExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $parameters = [];

    /**
     * Create predicate expression
     *
     * @param $if
     * @param $then
     * @param $else
     */
    function __construct($if, $then, $else = null)
    {
        $this->parameters['if'] = $if;
        $this->parameters['then'] = $then;
        $this->parameters['else'] = $else;
    }

    function compile()
    {
        return $this->parameters['if'].' ? '.$this->parameters['then'].' : '.$this->parameters['else'];
    }

}