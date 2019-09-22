<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\InClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithClause;

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
     * @param string $aql
     * @param null $bindings
     * @param null $collections
     * @return $this
     */
    public function raw(string $aql, $bindings = [], $collections = [])
    {
        $this->addCommand(new RawClause($aql));

        return $this;
    }

    public function with()
    {
        $collections = func_get_args();
        foreach ($collections as $key => $collection) {
            $collections[$key] = $this->normalizeArgument($collection, 'collection');
        }

        $this->addCommand(new WithClause($collections));

        return $this;
    }

    /**
     * Create a for clause
     *
     * @param string|array $variableName
     * @param mixed $in
     * @return $this
     */
    public function for($variableName, $in)
    {
        if (! is_array($variableName)) {
            $variableName = [$variableName];
        }

        foreach ($variableName as $key => $value) {
            $variableName[$key] = $this->normalizeArgument($value, 'variable');
        }

        $in = $this->normalizeArgument($in, ['collection', 'range', 'list', 'function', 'query']);

        $this->addCommand(new ForClause($variableName, $in));

        return $this;
    }

    public function filter($leftOperand, $rightOperand = null, $comparisonOperator = '==', $logicalOperator = 'AND')
    {
        $this->addCommand(new FilterClause($leftOperand, $rightOperand, $comparisonOperator, $logicalOperator));

        return $this;
    }

    public function return($expression, $distinct = false)
    {
        $this->addCommand(new ReturnClause($expression, $distinct));

        return $this;
    }
}
