<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryElement;

/**
 * Class Clause.
 *
 * Data & formatting objects for AQL Clauses.
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class Clause extends QueryElement
{
    public function __construct() {}
}
