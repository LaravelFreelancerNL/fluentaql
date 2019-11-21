<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class RawClause extends Clause
{
    protected $aql;

    /**
     * RawClause constructor.
     * @param string $aql
     */
    public function __construct(string $aql)
    {
        parent::__construct();

        $this->aql = $aql;
    }

    /**
     * Generate output.
     * @return string
     */
    public function compile()
    {
        return (string) $this->aql;
    }
}
