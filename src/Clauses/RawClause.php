<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class RawClause extends Clause
{
    protected string $aql;

    public function __construct(string $aql)
    {
        parent::__construct();

        $this->aql = $aql;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        return $this->aql;
    }
}
