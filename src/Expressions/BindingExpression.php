<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression
 */
class BindingExpression extends Expression implements ExpressionInterface
{
    protected $expression;

    protected $type;

    function __construct($expression, $type = 'variable')
    {
        parent::__construct($expression);

        $this->type = $type;
    }

    function compile(QueryBuilder $qb)
    {
        $binding = $this->expression;

        if (stripos($this->expression, '@') === 0) {
            $binding = "`{$binding}`";
        }

        $prefix = '@';
        if ($this->type == 'collection') {
            $prefix = '@@';
        }
        $binding = $prefix.$binding;

        return $binding;
    }
}

