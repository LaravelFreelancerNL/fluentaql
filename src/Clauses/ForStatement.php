<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class ForStatement extends Statement
{

    protected $variableName;

    protected $in;

    function __construct($variableName, $in)
    {
        $this->variableName = $variableName;
        $this->in = $in;
    }

    public function compile()
    {
        $query = "FOR {$this->variableName} IN {$this->in}";
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
        return $this->compile()['query'];
    }

}