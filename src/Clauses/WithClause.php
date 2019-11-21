<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class WithClause extends Clause
{
    protected $collections;

    /**
     * WithClause constructor.
     * @param array $collections
     */
    public function __construct($collections)
    {
        parent::__construct();

        $this->collections = $collections;
    }

    public function compile()
    {
        $expression = implode(', ', $this->collections);

        return "WITH {$expression}";
    }
}
