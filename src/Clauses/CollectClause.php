<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;

class CollectClause extends Clause
{
    protected $variableName;
    protected $expression;

    public function __construct($variableName = null, ExpressionInterface $expression = null)
    {
        $this->variableName = $variableName;
        $this->expression = $expression;
    }

    public function compile()
    {
        $output = 'COLLECT';
        if (isset($this->variableName) && isset($this->expression)) {
            $output .= ' '.$this->variableName.' = '.$this->expression;
        }

        return $output;
    }
}
