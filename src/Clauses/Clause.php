<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

/**
 * Class Clause
 *
 * Data & formatting objects for AQL clauses.
 *
 * @package LaravelFreelancerNL\FluentAQL\Clauses
 */
abstract class Clause
{
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->compile();
    }
}
