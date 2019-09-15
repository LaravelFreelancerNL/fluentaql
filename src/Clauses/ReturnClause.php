<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class ReturnClause extends Clause
{
    protected $expression;

    public function __construct($expression)
    {
        parent::__construct();

        $this->expression = $expression;
    }

    public function compile()
    {
        return "RETURN {$this->expression}";
    }
}
