<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\Traits\NormalizesFunctions;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class FunctionExpression extends Expression implements ExpressionInterface
{
    use NormalizesFunctions;

    protected string $functionName;

    /**
     * @var array<mixed>
     */
    protected $parameters = [];

    /**
     * FunctionExpression constructor.
     *
     * @param string $functionName
     * @param mixed $parameters
     */
    public function __construct(string $functionName, mixed $parameters = [])
    {
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
        $output = strtoupper($this->functionName) . '(';
        $output .= implode(', ', $this->compileParameters($this->parameters, $queryBuilder));
        $output .= ')';

        return $output;
    }

    /**
     * @param array<mixed>$parameters
     * @param QueryBuilder $queryBuilder
     * @return array<mixed>
     */
    protected function compileParameters(
        array $parameters,
        QueryBuilder $queryBuilder
    ): array {
        $compiledParameters = [];
        foreach ($parameters as $key => $parameter) {
            if ($key === 'predicates') {
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

        return 'normalize' . str_replace(' ', '', $value);
    }
}
