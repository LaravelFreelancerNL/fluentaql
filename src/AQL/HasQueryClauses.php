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
    /**
     * Use with extreme caution, as no safety checks are done at all!
     * You HAVE TO prepare user input yourself or be open to injection attacks.
     *
     * @param string     $aql
     * @param null       $binds
     * @param array|null $collections
     *
     * @return QueryBuilder
     */
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

        $this->addCommand(new RawClause($aql));

        return $this;
    }

    public function options($options): QueryBuilder
    {
        $options = $this->normalizeArgument($options, 'Object');

        $this->addCommand(new OptionsClause($options));

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

        foreach ($variableName as $key => $value) {
            $variableName[$key] = $this->normalizeArgument($value, 'Variable');
            $this->registerVariable($variableName[$key]);
        }

        if ($in !== null) {
            $in = $this->normalizeArgument($in, ['Collection', 'Range', 'List', 'Query']);
        }

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
     * @param mixed  $value
     * @param string $logicalOperator
     *
     * @return QueryBuilder
     */
    public function filter(
        $attribute,
        $comparisonOperator = '==',
        $value = null,
        $logicalOperator = 'AND'
    ): QueryBuilder {
        //create array of predicates if $leftOperand isn't an array already
        if (is_string($attribute)) {
            $attribute = [[$attribute, $comparisonOperator, $value, $logicalOperator]];
        }

        $predicates = $this->normalizePredicates($attribute);

        $this->addCommand(new FilterClause($predicates));

        return $this;
    }

    /**
     * Search a view.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-search.html
     *
     * @param string $attribute
     * @param string $comparisonOperator
     * @param mixed  $value
     * @param string $logicalOperator
     *
     * @return QueryBuilder
     */
    public function search(
        $attribute,
        $comparisonOperator = '==',
        $value = null,
        $logicalOperator = 'AND'
    ): QueryBuilder {
        //create array of predicates if $leftOperand isn't an array already
        if (is_string($attribute)) {
            $attribute = [[$attribute, $comparisonOperator, $value, $logicalOperator]];
        }

        $predicates = $this->normalizePredicates($attribute);

        $this->addCommand(new SearchClause($predicates));

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
        if (isset($variableName)) {
            $variableName = $this->normalizeArgument($variableName, 'Variable');
        }
        if (isset($expression)) {
            $expression = $this->normalizeArgument($expression, ['Reference', 'Function', 'Query', 'Bind']);
        }

        $this->addCommand(new CollectClause($variableName, $expression));

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
        $groupsVariable = $this->normalizeArgument($groupsVariable, 'Variable');
        $this->registerVariable($groupsVariable);

        if (isset($projectionExpression)) {
            $projectionExpression = $this->normalizeArgument(
                $projectionExpression,
                ['Reference', 'Object', 'Function', 'Query', 'Bind']
            );
        }

        $this->addCommand(new GroupClause($groupsVariable, $projectionExpression));

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
        $keepVariable = $this->normalizeArgument($keepVariable, 'Variable');
        $this->registerVariable($keepVariable);

        $this->addCommand(new KeepClause($keepVariable));

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
        $countVariableName = $this->normalizeArgument($countVariableName, 'Variable');
        $this->registerVariable($countVariableName);

        $this->addCommand(new WithCountClause($countVariableName));

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
        $variableName = $this->normalizeArgument($variableName, 'Variable');
        $this->registerVariable($variableName);

        $aggregateExpression = $this->normalizeArgument(
            $aggregateExpression,
            ['Reference', 'Function', 'Query', 'Bind']
        );

        $this->addCommand(new AggregateClause($variableName, $aggregateExpression));

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
    public function sort($sortBy = null, $direction = null): QueryBuilder
    {
        $sortExpressions = [];

        //normalize string|null $by and $direction
        if (is_string($sortBy) || $sortBy == null) {
            $sortExpressions[] = $this->normalizeSortExpression($sortBy, $direction);
        }

        if (is_array($sortBy)) {
            //Wandel door de array
            $sortExpressions = array_map(function ($expression) {
                return $this->normalizeSortExpression($expression);
            }, $sortBy);
        }

        $this->addCommand(new SortClause($sortExpressions));

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
        if ($count !== null) {
            $count = (int) $count;
        }

        $this->addCommand(new LimitClause($offsetOrCount, $count));

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
        $expression = $this->normalizeArgument(
            $expression,
            ['Boolean', 'Object', 'List', 'Function', 'Variable', 'Reference', 'Query', 'Bind']
        );

        $this->addCommand(new ReturnClause($expression, $distinct));

        return $this;
    }
}
