<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class GroupClause extends Clause
{
    protected $groupsVariable;
    protected $projectionExpression;

    public function __construct($groupsVariable, $projectionExpression = null)
    {
        $this->groupsVariable = $groupsVariable;
        $this->projectionExpression = $projectionExpression;
    }

    public function compile()
    {
        $output = 'INTO '.$this->groupsVariable;
        if (isset($this->projectionExpression)) {
            $output .= ' = '.$this->projectionExpression;
        }

        return $output;
    }
}
