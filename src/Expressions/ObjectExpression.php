<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

/**
 * Key expression.
 */
class ObjectExpression extends Expression implements ExpressionInterface
{
    public function compile()
    {
        $output = '';
        foreach ($this->expression as $key => $value) {
            if ($output != '') {
                $output .= ',';
            }
            $output .= '"'.$key.'":'.$value;
        }

        return '{'.$output.'}';
    }
}
