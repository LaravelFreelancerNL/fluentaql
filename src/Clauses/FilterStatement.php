<?php
namespace LaravelFreelancerNL\FluentAQL\Clauses;

use Closure;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;

class FilterStatement extends Statement
{

    protected $filters = [];

    /**
     * Filter statement.
     *
     * @param  string|array|\Closure  $leftOperand
     * @param  mixed   $comparisonOperator
     * @param  mixed   $rightOperand
     * @param  string  $logicalOperator
     * @return $this
     */
    function __construct($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = 'AND')
    {
        // If the left operand is an array, we will assume it is an array of key-value pairs
        // and can add them each as a where clause. We will maintain the boolean we
        // received when the method was called and pass it into the nested where.
        if (is_array($leftOperand)) {
            return $this->addMultipleFilters($leftOperand, $logicalOperator);
        }

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is a double equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$rightOperand, $comparisonOperator] = $this->prepareValueAndOperator($rightOperand, $comparisonOperator, func_num_args() === 2);

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '==' operators and
        // we will set the operators to '==' and set the values appropriately.
        if ($this->invalidOperator($comparisonOperator)) {
            [$rightOperand, $comparisonOperator] = [$comparisonOperator, '='];
        }

        $this->filters[] = compact(
            'leftOperand', 'comparisonOperator ', 'rightOperand', 'logicalOperator'
        );

        if (! $rightOperand instanceof Expression) {
            $this->addBinding($rightOperand, 'where');
        }

        return $this;
    }

    public function compile()
    {
        $filters = $this->removeLastLogicalOperator();
        foreach ($this->filters as $filter) {
            $compiledFilters[] = implode(' ', $filter);
        }
    }

    public function removeLastLogicalOperator()
    {
        $filters = $this->filters;
        unset(end($filters['logicalOperator']));
        $this->filters = $filters;
    }


}