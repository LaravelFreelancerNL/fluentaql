<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class ReturnClause extends Clause
{
    protected $expression;
    protected $distinct;

    public function __construct($expression, $distinct = false)
    {
        parent::__construct();

        $this->expression = $expression;
        $this->distinct = $distinct;
    }

    public function compile()
    {
        $output = 'RETURN';
        if ($this->distinct) {
            $output .= ' DISTINCT';
        }

        return $output . ' ' . $this->expression;
    }
}
