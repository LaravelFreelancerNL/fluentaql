<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class KeepClause extends Clause
{
    protected $keepVariable;

    public function __construct($keepVariable)
    {
        $this->keepVariable = $keepVariable;
    }

    public function compile()
    {
        return "KEEP ".$this->keepVariable;
    }
}
