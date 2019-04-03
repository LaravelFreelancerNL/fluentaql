<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\InClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;

/**
 * Trait hasClauses
 * API calls to add clause commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasClauses
{

    /**
     * Use with extreme caution, as no safety checks are done at all!
     * You HAVE TO prepare user input yourself or be open to injection attacks.
     * @param $aql
     * @param null $bindings
     * @param null $collections
     * @return $this
     */
    public function raw($aql, $bindings = [], $collections = [])
    {
        $this->addCommand(new RawClause($aql, $bindings, $collections));

        return $this;

    }

    public function filter($column, $operator, $value, $boolean)
    {
        $this->addCommand(new FilterClause($column, $operator, $value, $boolean));

        return $this;
    }

    public function in($list)
    {
        $this->addCommand(new InClause($list));

        return $this;
    }
}