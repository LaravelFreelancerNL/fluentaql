<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;


use LaravelFreelancerNL\FluentAQL\Grammar;

abstract class Clause
{

    protected $grammar;

    function __construct()
    {
        $this->grammar = new Grammar();
    }

    function __toString()
    {
        return $this->compile()['query'];
    }
}