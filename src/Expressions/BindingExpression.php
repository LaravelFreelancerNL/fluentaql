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

    public function __construct($expression, $type = 'variable')
    {
        parent::__construct($expression);

        $this->type = $type;
    }

    public function compile()
    {
        $binding = $this->expression;

        if (stripos($this->expression, '@') === 0) {
            $binding = "`{$binding}`";
        }

        $prefix = '@';
        if ($this->type == 'collection') {
            $prefix = '@@';
        }
        return $prefix.$binding;
    }
}
