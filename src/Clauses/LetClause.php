<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class LetClause extends Clause
{
    protected $variableName;

    protected $value;

    public function __construct($variableName, $value)
    {
        parent::__construct();

        $this->variableName = $variableName;
        $this->value = $value;
    }

    public function compile()
    {
        return "LET {$this->variableName} = {$this->value}";
    }
}
