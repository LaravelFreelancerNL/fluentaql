<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ReturnClause extends Clause
{
    protected mixed $expression;

    protected bool $distinct;

    /**
     * ReturnClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        mixed $expression,
        bool $distinct = false
    ) {
        parent::__construct();

        $this->expression = $expression;
        $this->distinct = $distinct;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->expression = $queryBuilder->normalizeArgument(
            $this->expression,
            ['Boolean', 'Object', 'List', 'Function', 'Variable', 'Reference', 'Query', 'Bind']
        );

        $output = 'RETURN';
        if ($this->distinct) {
            $output .= ' DISTINCT';
        }

        return $output.' '.$this->expression->compile($queryBuilder);
    }
}
