<?php
namespace LaravelFreelancerNL\FluentAQL\Statements;

class ForStatement extends Statement
{

    protected $variableName;

    protected $value;

    function __construct($variableName, $value)
    {
        parent::__construct();

        $this->variableName = $variableName;
        $this->value = $value;
    }

    public function compile()
    {
        $query = "LET {$this->variable} = {$this->value}";
        $bindings = [];
        $collections = [];

        return [
            'query' => $query,
            'bindings' => $bindings,
            'collections' => $collections
        ];
    }
}