<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;

class ForClause extends Clause
{
    protected $variables;

    protected $in;

    /**
     * ForClause constructor.
     * @param array $variableName
     * @param ExpressionInterface $in
     */
    public function __construct($variableName, $in = null)
    {
        parent::__construct();

        $this->variables = $variableName;

        $this->in = $in;
    }

    public function compile()
    {
        $variableExpression = implode(', ', $this->variables);

        $inExpression = (string) $this->in;
        if (is_array($this->in)) {
            $inExpression = '['.implode(', ', $this->in).']';
        }

        return "FOR {$variableExpression} IN {$inExpression}";
    }
}
