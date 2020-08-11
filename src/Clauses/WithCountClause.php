<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class WithCountClause extends Clause
{
    protected $countVariableName;

    public function __construct($countVariableName)
    {
        $this->countVariableName = $countVariableName;
    }

    public function compile()
    {
        return 'WITH COUNT INTO '.$this->countVariableName;
    }
}
