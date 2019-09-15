<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\InClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;

/**
 * Trait hasQueryClauses
 * API calls to add clause commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasQueryClauses
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


    public function for($vertexVariableName, $edgeVariableName = null, $pathVariableName = null)
    {
        $this->addCommand(new ForClause($vertexVariableName, $edgeVariableName, $pathVariableName));

        return $this;
    }

    public function in($list)
    {
        $this->addCommand(new InClause($list));

        return $this;
    }

    public function filter($leftOperand, $rightOperand = null, $comparisonOperator = '==', $logicalOperator = 'AND')
    {
        $this->addCommand(new FilterClause($leftOperand, $rightOperand, $comparisonOperator, $logicalOperator));

        return $this;
    }

    public function return($expression)
    {
        $this->addCommand(new ReturnClause($expression));

        return $this;
    }
}
