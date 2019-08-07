<?php
namespace LaravelFreelancerNL\FluentAQL\Statements;

class ReturnStatement extends Statement
{
    protected $expression;

    function __construct($expression)
    {
        parent::__construct();

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
}