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

    public function compile(QueryBuilder $queryBuilder): string
    {
        foreach ($this->variables as $key => $value) {
            $this->variables [$key] = $queryBuilder->normalizeArgument($value, 'Variable');
            $queryBuilder->registerVariable($this->variables[$key]->compile($queryBuilder));
        }
        $variableExpression =  array_map(function ($variable) use ($queryBuilder) {
            return $variable->compile($queryBuilder);
        }, $this->variables);

        $variableExpression = implode(', ', $variableExpression);

        $inExpression = '';
        if ($this->in !== null) {
            $this->in = $queryBuilder
                ->normalizeArgument($this->in, ['Collection', 'Range', 'List', 'Reference', 'Query', 'CollectionBind']);
            $inExpression = $this->in->compile($queryBuilder);
        }

        return "FOR {$variableExpression} IN {$inExpression}";
    }
}
