<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class LimitClause extends Clause
{
    protected int|QueryBuilder|Expression $count;

    protected null|int|QueryBuilder|Expression $offset;

    public function __construct(
        int|QueryBuilder|Expression $offsetOrCount,
        int|QueryBuilder|Expression $count = null
    ) {
        if ($count === null) {
            $this->count = $offsetOrCount;
            $this->offset = null;
        }
        if ($count !== null) {
            $this->count = $count;
            $this->offset = $offsetOrCount;
        }
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->count = $queryBuilder->normalizeArgument($this->count, ['Number', 'Reference', 'Query', 'Bind']);



        $output = 'LIMIT ';
        if ($this->offset !== null) {
            $this->offset = $queryBuilder->normalizeArgument($this->offset, ['Number', 'Reference', 'Query', 'Bind']);

            $output .= $this->offset->compile($queryBuilder) . ', ';
        }

        return $output . $this->count->compile($queryBuilder);
    }
}
