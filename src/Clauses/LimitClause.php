<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Expressions\NumericExpression;

class LimitClause extends Clause
{
    protected $count;

    protected $offset;

    /**
     * ForClause constructor.
     *
     * @param NumericExpression $offsetOrCount
     * @param NumericExpression|null $count
     */
    public function __construct(NumericExpression $offsetOrCount, NumericExpression $count = null)
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
            $output .= $this->offset . ', ';
        }
        return $output.$this->count;
    }
}
