<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;

class RawClause extends Clause
{
    protected $expression;

    protected $bindings = [];

    protected $collections = [];

    public function __construct($aql, $bindings = [], $collections = [])
    {
        parent::__construct();

        $this->expression = new LiteralExpression($aql);

        $this->bindings = $bindings;

        $this->collections = $collections;
    }


    public function compile()
    {
        return $this->expression;
    }
}
