<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;


class InClause extends Clause
{

    protected $expression;

    function __construct($expression)
    {
        parent::__construct();

        $this->expression = $this->grammar->normalizeArgument($expression, ['literal', 'range', 'list', 'query']);
    }


    public function compile()
    {
        $query = "IN {$this->expression}";
        $bindings = [];
        $collections = [];

        return [
            'query' => $query,
            'bindings' => $bindings,
            'collections' => $collections
        ];
    }
}