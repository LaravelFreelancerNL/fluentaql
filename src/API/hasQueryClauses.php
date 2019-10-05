<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\InClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SortClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithClause;
use LaravelFreelancerNL\FluentAQL\Expressions\NullExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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
     *
     * @param string $aql
     * @param null $bindings
     * @param null $collections
     * @return $this
     */
    public function raw(string $aql, $bindings = [], $collections = []) : QueryBuilder
    {
        $this->addCommand(new RawClause($aql));

        return $this;
    }

    /**
     * Create a for clause
     * @link https://www.arangodb.com/docs/3.4/aql/operations-for.html
     *
     * @param string|array $variableName
     * @param mixed $in
     * @return $this
     */
    public function for($variableName, $in) : QueryBuilder
    {
        if (! is_array($variableName)) {
            $variableName = [$variableName];
        }

        foreach ($variableName as $key => $value) {
            $variableName[$key] = $this->normalizeArgument($value, 'variable');
        }

        $in = $this->normalizeArgument($in, ['collection', 'range', 'list', 'query']);

        $this->addCommand(new ForClause($variableName, $in));

        return $this;
    }

    /**
     * Filter results from a for clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-filter.html
     *
     * @param string $attribute
     * @param string $comparisonOperator
     * @param mixed $value
     * @param string $logicalOperator
     * @return QueryBuilder
     */
    public function filter($attribute, $comparisonOperator = '==', $value = null,  $logicalOperator = 'AND') : QueryBuilder
    {
        //create array of predicates if $leftOperand isn't an array already
        if (is_string($attribute)) {
            $attribute = [[$attribute, $comparisonOperator, $value,  $logicalOperator]];
        }

        $predicates = $this->normalizePredicates($attribute);

        $this->addCommand(new FilterClause($predicates));

        return $this;
    }

    /**
     * Sort documents to return
     * @link https://www.arangodb.com/docs/stable/aql/operations-sort.html
     * @param null $sortBy
     * @param null $direction
     * @return QueryBuilder
     */
    public function sort($sortBy = null, $direction = null) : QueryBuilder
    {
        $sortExpressions = [];

        //normalize string|null $by and $direction
        if (is_string($sortBy) || $sortBy == null) {
            $sortExpressions[] = $this->normalizeSortExpression($sortBy, $direction);
        }

        if (is_array($sortBy)) {
            //Wandel door de array
            $sortExpressions = array_map(function($expression) {
                return $this->normalizeSortExpression($expression);
            }, $sortBy);
        }

        $this->addCommand(new SortClause($sortExpressions));

        return $this;
    }

    /**
     * Return data
     * @link https://www.arangodb.com/docs/3.4/aql/operations-return.html
     *
     * @param $expression
     * @param bool $distinct
     * @return QueryBuilder
     */
    public function return($expression, $distinct = false) : QueryBuilder
    {
        $this->addCommand(new ReturnClause($expression, $distinct));

        return $this;
    }
}
