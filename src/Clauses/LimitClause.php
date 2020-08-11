<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class LimitClause extends Clause
{
    protected $count;

    protected $offset;

    /**
     * ForClause constructor.
     *
     * @param int      $offsetOrCount
     * @param int|null $count
     */
    public function __construct(int $offsetOrCount, int $count = null)
    {
        if ($count === null) {
            $this->count = $offsetOrCount;
            $this->offset = null;
        }
        if ($count !== null) {
            $this->count = $count;
            $this->offset = $offsetOrCount;
        }
    }

    public function compile()
    {
        $output = 'LIMIT ';
        if ($this->offset !== null) {
            $output .= $this->offset.', ';
        }

        return $output.$this->count;
    }
}
