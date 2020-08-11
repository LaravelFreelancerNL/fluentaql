<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class OptionsClause extends Clause
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function compile()
    {
        return 'OPTIONS '.$this->options;
    }
}
