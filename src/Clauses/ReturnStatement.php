<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;

class ReturnStatement extends Statement
{
    protected $expression;

    function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function compile()
    {
        $query = "RETURN {$this->expression}";
        $bindings = [];
        $collections = [];

        return [
            'query' => $query,
            'bindings' => $bindings,
            'collections' => $collections
        ];
    }

    function __toString()
    {
        return 'TEST123';
//        return $this->compile()['query'];
    }
}