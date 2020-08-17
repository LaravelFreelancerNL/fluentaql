<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\AggregateClause;
use LaravelFreelancerNL\FluentAQL\Clauses\CollectClause;
use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\GroupClause;
use LaravelFreelancerNL\FluentAQL\Clauses\KeepClause;
use LaravelFreelancerNL\FluentAQL\Clauses\LimitClause;
use LaravelFreelancerNL\FluentAQL\Clauses\OptionsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SearchClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SortClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithCountClause;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasQueryClauses
 * API calls to add clause commands to the builder.
 */
trait HasQueryClauses
{

    public function raw(string $aql, $binds = null, $collections = null): QueryBuilder
    {
        if (is_array($binds)) {
            foreach ($binds as $key => $value) {
                $this->bind($value, $key);
            }
        }
        if (is_array($binds)) {
            foreach ($collections as $mode => $modeCollections) {
                $this->registerCollections($modeCollections, $mode);
            }
        }

        $this->addClause(new RawClause($aql));

        return $this;
    }


    /**
     * Create a for clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-for.html
     *
     * @param string|array $variableName
     * @param mixed        $in
     *
     * @return QueryBuilder
     */
    public function for($variableName, $in = null): QueryBuilder
    {
        if (!is_array($variableName)) {
            $variableName = [$variableName];
        }

        $this->addClause(new ForClause($variableName, $in));

        return $this;
    }

    /**
     * Filter results from a for clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-filter.html
     *
     * @param mixed $leftOperand
     * @param string|array|null $comparisonOperator
     * @param mixed  $rightOperand
     * @param string $logicalOperator
     *
     * @return QueryBuilder
     */
    public function filter(
        $leftOperand,
        $comparisonOperator = null,
        $rightOperand = null,
        $logicalOperator = null
    ): QueryBuilder {
        $predicates = $leftOperand;
        if (is_string($comparisonOperator)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addClause(new FilterClause($predicates));

        return $this;
    }

    /**
     * Search a view.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-search.html
     *
     * @param $leftOperand
     * @param  string  $comparisonOperator
     * @param  null  $rightOperand
     * @param  string  $logicalOperator
     *
     * @return QueryBuilder
     */
    public function search(
        $leftOperand,
        $comparisonOperator = null,
        $rightOperand = null,
        $logicalOperator = null
    ): QueryBuilder {
        $predicates = $leftOperand;
        if (is_string($comparisonOperator)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addClause(new SearchClause($predicates));

        return $this;
    }

    /**
     * Collect clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html
     *
     * @param string|null $variableName
     * @param null        $expression
     *
     * @return QueryBuilder
     */
    public function collect($variableName = null, $expression = null): QueryBuilder
    {

        $this->addClause(new CollectClause($variableName, $expression));

        return $this;
    }

    /**
     * Group clause:
     * Creates the INTO clause of a collect clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#grouping-syntaxes
     *
     * @param $groupsVariable
     * @param null $projectionExpression
     *
     * @return QueryBuilder
     */
    public function group($groupsVariable, $projectionExpression = null): QueryBuilder
    {
        $this->addClause(new GroupClause($groupsVariable, $projectionExpression));

        return $this;
    }

    /**
     * Keep clause
     * Limits the attributes of the data that is grouped.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#discarding-obsolete-variables
     *
     * @param $keepVariable
     *
     * @return QueryBuilder
     */
    public function keep($keepVariable): QueryBuilder
    {
        $this->addClause(new KeepClause($keepVariable));

        return $this;
    }

    /**
     * withCount clause
     * Count the collected and grouped data.
     * withCount can only be used after a group clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#group-length-calculation
     *
     * @param $countVariableName
     *
     * @return QueryBuilder
     */
    public function withCount($countVariableName): QueryBuilder
    {
        $this->addClause(new WithCountClause($countVariableName));

        return $this;
    }

    /**
     * Aggregate clause
     * Creates the INTO clause of a collect clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#aggregation
     *
     * @param $variableName
     * @param $aggregateExpression
     *
     * @return QueryBuilder
     */
    public function aggregate($variableName, $aggregateExpression): QueryBuilder
    {
        $this->addClause(new AggregateClause($variableName, $aggregateExpression));

        return $this;
    }

    /**
     * Sort documents to return.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-sort.html
     *
     * @param null $sortBy
     * @param null $direction
     *
     * @return QueryBuilder
     */
    public function sort(): QueryBuilder
    {
        $this->addClause(new SortClause(func_get_args()));

        return $this;
    }

    /**
     * Limit results.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-limit.html
     *
     * @param int $offsetOrCount
     * @param int $count
     *
     * @return $this
     */
    public function limit(int $offsetOrCount, int $count = null)
    {
        $this->addClause(new LimitClause($offsetOrCount, $count));

        return $this;
    }

    /**
     * Return data.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-return.html
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param $expression
     * @param bool $distinct
     *
     * @return QueryBuilder
     */
    public function return($expression, $distinct = false): QueryBuilder
    {
        $this->addClause(new ReturnClause($expression, $distinct));

        return $this;
    }

    public function options($options): QueryBuilder
    {
        $this->addClause(new OptionsClause($options));

        return $this;
    }
}
