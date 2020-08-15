<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class CollectClause extends Clause
{

    protected $variableName;

    protected $expression;

    /**
     * CollectClause constructor.
     * @param  mixed  $variableName
     * @param  mixed  $expression
     */
    public function __construct($variableName = null, $expression = null)
    {
        $this->variableName = $variableName;
        $this->expression = $expression;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        if (isset($this->variableName)) {
            $this->variableName = $queryBuilder->normalizeArgument($this->variableName, 'Variable');
        }
        if (isset($this->expression)) {
            $this->expression = $queryBuilder->normalizeArgument($this->expression, ['Reference', 'Function', 'Query', 'Bind']);
        }

        $output = 'COLLECT';
        if (isset($this->variableName) && isset($this->expression)) {
            $output .= ' ' . $this->variableName->compile($queryBuilder) . ' = ' . $this->expression->compile($queryBuilder);
        }

        return $output;
    }
}
