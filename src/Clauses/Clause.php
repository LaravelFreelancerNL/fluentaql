<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Grammar;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Class Clause
 * Any query clause be it primary or secondary clauses.
 *
 * @package LaravelFreelancerNL\FluentAQL\Clauses
 */
abstract class Clause
{
    protected $grammar;

    public function __construct()
    {
        $this->grammar = new Grammar();
    }

    public function __toString()
    {
        return $this->compile();
    }
}
