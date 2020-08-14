<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ForClause extends Clause
{
    protected $variables;

    protected $in;

    /**
     * ForClause constructor.
     *
     * @param $variables
     * @param  ExpressionInterface  $in
     */
    public function __construct($variables, $in = null)
    {
        parent::__construct();

        $this->variables = $variables;

        $this->in = $in;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        foreach ($this->variables  as $key => $value) {
            $this->variables [$key] = $queryBuilder->normalizeArgument($value, 'Variable');
            $queryBuilder->registerVariable($this->variables [$key]);
        }
        $variableExpression = implode(', ', $this->variables);

        if ($this->in !== null) {
            $this->in = $queryBuilder
                ->normalizeArgument($this->in, ['Collection', 'Range', 'List', 'Reference', 'Query', 'Bind'])
                ->compile($queryBuilder);
        }
        $inExpression = (string) $this->in;

        return "FOR {$variableExpression} IN {$inExpression}";
    }
}
