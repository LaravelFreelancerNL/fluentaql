<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ReturnClause extends Clause
{
    protected $expression;
    protected $distinct;

    /**
     * ReturnClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param $expression
     * @param bool $distinct
     */
    public function __construct($expression, $distinct = false)
    {
        parent::__construct();

        $this->expression = $expression;
        $this->distinct = $distinct;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        $this->expression = $queryBuilder->normalizeArgument(
            $this->expression,
            ['Boolean', 'Object', 'List', 'Function', 'Variable', 'Reference', 'Query', 'Bind']
        );

        $output = 'RETURN';
        if ($this->distinct) {
            $output .= ' DISTINCT';
        }

        return $output . ' ' . $this->expression->compile($queryBuilder);
    }
}
