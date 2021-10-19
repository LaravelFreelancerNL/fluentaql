<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ForClause extends Clause
{
    /**
     * @var array<string|Expression>
     */
    protected array $variables;

    /**
     * @var array<mixed>|Expression|ExpressionInterface|QueryBuilder|string|null
     */
    protected array|Expression|ExpressionInterface|QueryBuilder|string|null $in;

    /**
     * @param array<string|Expression> $variables
     * @param array<mixed>|string|QueryBuilder|Expression|null $in
     */
    public function __construct(
        array $variables,
        array|string|QueryBuilder|Expression $in = null
    ) {
        parent::__construct();

        $this->variables = $variables;

        $this->in = $in;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $variables = [];
        foreach ($this->variables as $key => $value) {
            $variables[$key] = $queryBuilder->normalizeArgument($value, 'Variable');
            $queryBuilder->registerVariable($variables[$key]->compile($queryBuilder));
        }
        $variableExpression =  array_map(function ($variable) use ($queryBuilder) {
            return $variable->compile($queryBuilder);
        }, $variables);

        $variableExpression = implode(', ', $variableExpression);

        $inExpression = '';
        if ($this->in !== null) {
            $this->in = $queryBuilder->normalizeArgument(
                $this->in,
                ['Collection', 'Range', 'List', 'Reference', 'Query', 'CollectionBind']
            );
            $inExpression = $this->in->compile($queryBuilder);
        }

        return "FOR {$variableExpression} IN {$inExpression}";
    }
}
