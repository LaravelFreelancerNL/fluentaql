<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class LimitClause extends Clause
{
    protected $count;

    protected $offset;

    /**
     * ForClause constructor.
     *
     * @param mixed      $offsetOrCount
     * @param mixed $count
     */
    public function __construct($offsetOrCount, $count = null)
    {
        if ($count === null) {
            $this->count = $offsetOrCount;
            $this->offset = null;
        }
        if ($count !== null) {
            $this->count = $count;
            $this->offset = $offsetOrCount;
        }
    }

    public function compile(QueryBuilder $queryBuilder)
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
