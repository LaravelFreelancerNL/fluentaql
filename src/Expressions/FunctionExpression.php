<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * AQL literal expression.
 */
class FunctionExpression extends Expression implements ExpressionInterface
{
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
     * @param string $functionName
     * @param array|string|null $parameters
     */
    public function __construct(string $functionName, $parameters = [])
    {
        parent::__construct($parameters);

        $this->functionName = $functionName;

        if (is_string($parameters)) {
            $parameters[] = $parameters;
        }
        if ($parameters === null) {
            $parameters = [];
        }
        $this->parameters = $parameters;
    }

    public function compile()
    {
        $output = strtoupper($this->functionName) . '(';
        $implosion = '';
        foreach ($this->parameters as $parameter) {
            $implosion .= ', ' . (string) $parameter;
        }
        if ($implosion != '') {
            $output .= ltrim($implosion, ', ');
        }

        return $output . ')';
    }
}
