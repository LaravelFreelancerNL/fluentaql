<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression
 */
class FunctionExpression extends Expression implements ExpressionInterface
{

    /**
     * name of the function
     *
     * @string $functionName
     */
    protected $functionName;

    /**
     * @var Expression[] $parameters
     */
    protected $parameters;

    /**
     * FunctionExpression constructor.
     * @param string $functionName
     * @param ExpressionInterface[] $parameters
     */
    function __construct(string $functionName, $parameters)
    {
        parent::__construct($parameters);

        if (is_string($parameters)) {
            $parameters[] = $parameters;
        }
        $this->parameters = $parameters;
    }

    function compile(QueryBuilder $qb)
    {
        $function = $this->functionName.'('.implode(', ', $this->arguments).')';

        return $function;
    }
}