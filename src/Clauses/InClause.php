<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class InClause extends Clause
{
    protected $expression;

    public function __construct($expression)
    {
        parent::__construct();

        $this->expression = $this->grammar->normalizeArgument($expression, ['literal', 'range', 'list', 'query']);
    }

    public function compile()
    {
        return "IN {$this->expression}";
    }
}
