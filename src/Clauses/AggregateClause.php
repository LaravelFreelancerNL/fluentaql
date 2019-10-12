<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Expressions\VariableExpression;

class AggregateClause extends Clause
{
    protected $variableName;
    protected $aggregateExpression;

    public function __construct($variableName, $aggregateExpression)
    {
        $this->variableName = $variableName;
        $this->aggregateExpression = $aggregateExpression;
    }

    public function compile()
    {
        return "AGGREGATE {$this->variableName} = {$this->aggregateExpression}";
    }
}
