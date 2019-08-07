<?php
namespace LaravelFreelancerNL\FluentAQL\Statements;

class LetStatement extends Statement
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
        $query = "LET {$this->variableName} = {$this->value}";
        $bindings = [];
        $collections = [];

        return [
            'query' => $query,
            'bindings' => $bindings,
            'collections' => $collections
        ];
    }
}