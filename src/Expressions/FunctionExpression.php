<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Traits\NormalizesFunctions;

/**
 * AQL literal expression.
 */
class FunctionExpression extends Expression implements ExpressionInterface
{
    use NormalizesFunctions;

    protected string $functionName;

    /**
     * @var array<array-key, mixed>
     */
    protected array $parameters = [];

    /**
     * FunctionExpression constructor.
     *
     * @param  string  $functionName
     * @param  array<array-key, mixed>|object|scalar|null  $parameters
     */
    public function __construct(
        string $functionName,
        object|array|string|int|float|bool|null $parameters = []
    ) {
        $this->functionName = $functionName;

        if (! is_array($parameters)) {
            $parameters = [$parameters];
        }

        $this->parameters = $parameters;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        if (! empty($this->parameters)) {
            $normalizeFunction = $this->getNormalizeFunctionName();
            $this->$normalizeFunction($queryBuilder);
        }

        $output = strtoupper($this->functionName).'(';
        $output .= implode(', ', $this->compileParameters($this->parameters, $queryBuilder));
        $output .= ')';

        return $output;
    }

    /**
     * @param  array<mixed>  $parameters
     * @param  QueryBuilder  $queryBuilder
     * @return array<string>
     */
    protected function compileParameters(
        array $parameters,
        QueryBuilder $queryBuilder
    ): array {
        $compiledParameters = [];

        /** @var Expression $parameter */
        foreach ($parameters as $key => $parameter) {
            if ($key === 'predicates') {
                /** @var PredicateExpression $parameter */
                $compiledParameters[] = $queryBuilder->compilePredicates($parameter);

                continue;
            }
            $compiledParameters[] = $parameter->compile($queryBuilder);
        }

        return $compiledParameters;
    }

    /**
     * Generate the name of the normalize function for this AQL function.
     */
    protected function getNormalizeFunctionName(): string
    {
        $value = ucwords(str_replace('_', ' ', strtolower($this->functionName)));

        return 'normalize'.str_replace(' ', '', $value);
    }
}
