<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class BindExpression extends LiteralExpression implements ExpressionInterface
{
    protected string $bindVariable;

    protected mixed $data = null;

    public function __construct(string $bindVariable, mixed $data = null)
    {
        $this->bindVariable = $bindVariable;

        $this->data = $data;
    }

    /**
     * Compile expression output.
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        return $this->bindVariable;
    }

    public function getBindVariable(): string
    {
        return $this->bindVariable;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
