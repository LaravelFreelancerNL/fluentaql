<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class WithCountClause extends Clause
{
    protected string|QueryBuilder|Expression $countVariableName;

    public function __construct(
        string|QueryBuilder|Expression $countVariableName
    ) {
        parent::__construct();

        $this->countVariableName = $countVariableName;
    }

    /**
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->countVariableName = $queryBuilder->normalizeArgument(
            $this->countVariableName,
            'Variable'
        );
        $queryBuilder->registerVariable($this->countVariableName);

        return 'WITH COUNT INTO ' . $this->countVariableName->compile($queryBuilder);
    }
}
