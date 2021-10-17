<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class LetClause extends Clause
{
    protected string|QueryBuilder|Expression $variableName;

    protected mixed $expression;

    public function __construct(
        string|QueryBuilder|Expression $variableName,
        mixed $expression
    ) {
        parent::__construct();

        $this->variableName = $variableName;
        $this->expression = $expression;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->variableName = $queryBuilder->normalizeArgument($this->variableName, 'Variable');
        $queryBuilder->registerVariable($this->variableName->compile($queryBuilder));

        $this->expression = $queryBuilder->normalizeArgument(
            $this->expression,
            ['List', 'Object', 'Query', 'Range', 'Number', 'Bind']
        );

        return "LET {$this->variableName->compile($queryBuilder)} = {$this->expression->compile($queryBuilder)}";
    }
}
