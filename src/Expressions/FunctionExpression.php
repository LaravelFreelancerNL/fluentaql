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

    /**
     * name of the function.
     *
     * @string $functionName
     */
    protected $functionName;

    /**
     * @var Expression[]
     */
    protected $parameters = [];

    /**
     * FunctionExpression constructor.
     *
     * @param string $functionName
     * @param mixed $parameters
     */
    public function __construct(string $functionName, $parameters = [])
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
        $implosion = '';
        foreach ($this->parameters as $parameter) {
            $implosion .= ', ' . $parameter->compile($queryBuilder);
        }
        if ($implosion != '') {
            $output .= ltrim($implosion, ', ');
        }
        $output .= ')';

        return $output;
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
