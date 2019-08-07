<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;


use LaravelFreelancerNL\FluentAQL\Grammar;

/**
 * Class Clause
 * Any query clause be it primary or secondary clauses.
 *
 * @package LaravelFreelancerNL\FluentAQL\Clauses
 */
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