<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

class ForClause extends Clause
{
    protected $variables;

    public function __construct($variableName, $edgeVariableName = null, $pathVariableName = null)
    {
        parent::__construct();

        $this->variables[] = $this->grammar->normalizeArgument($variableName, 'variable');
        $this->variables[] = $edgeVariableName;
        $this->variables[] = $pathVariableName;
        $this->variables = array_filter($this->variables);
    }

    public function compile()
    {
        $expression = implode(', ', $this->variables);

        return "FOR {$expression}";
    }
}
