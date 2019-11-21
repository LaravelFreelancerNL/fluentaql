<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class LetClause extends Clause
{
    protected $variableName;

    protected $expression;

    public function __construct($variableName, $expression)
    {
        parent::__construct();

        $this->variableName = $variableName;
        $this->expression = $expression;
    }

    public function compile()
    {
        return "LET {$this->variableName} = {$this->expression}";
    }
}
