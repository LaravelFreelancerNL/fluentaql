<?php
namespace LaravelFreelancerNL\FluentAQL\Statements;

class ForStatement extends Statement
{

    protected $variables;

    protected $edgeVariableName;

    protected $pathVariableName;

    function __construct($variableName, $edgeVariableName = null, $pathVariableName = null)
    {
        parent::__construct();

        $this->variables[] = $this->grammar->normalizeArgument($variableName, 'variable');
        $this->variables[] = $edgeVariableName;
        $this->variables[] = $pathVariableName;
        $this->variables = array_filter($this->variables);
    }

    public function compile()
    {
        $variables = implode(', ', $this->variables);

        $query = "FOR {$variables}";
        $bindings = [];
        $collections = [];

        return [
            'query' => $query,
            'bindings' => $bindings,
            'collections' => $collections
        ];
    }
}