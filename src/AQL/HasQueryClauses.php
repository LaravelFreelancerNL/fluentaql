<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\AggregateClause;
use LaravelFreelancerNL\FluentAQL\Clauses\CollectClause;
use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\IntoClause;
use LaravelFreelancerNL\FluentAQL\Clauses\KeepClause;
use LaravelFreelancerNL\FluentAQL\Clauses\LimitClause;
use LaravelFreelancerNL\FluentAQL\Clauses\OptionsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SearchClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SortClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithCountClause;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasQueryClauses
 * API calls to add clause commands to the builder.
 */
trait HasQueryClauses
{

    abstract public function addCommand($command);

    /**
     * Create a for clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-for.html
     *
     * @param string|array|ExpressionInterface $variableName
     * @param mixed        $in
     *
     * @return QueryBuilder
     */
    public function for($variableName, $in = null): self
    {
        if (!is_array($variableName)) {
            $variableName = [$variableName];
        }

        $this->addCommand(new ForClause($variableName, $in));

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
    ): self {
        $predicates = $leftOperand;
        if (is_string($comparisonOperator)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addCommand(new FilterClause($predicates));

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
    ): self {
        $predicates = $leftOperand;
        if (is_string($comparisonOperator)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addCommand(new SearchClause($predicates));

        return $this;
    }

    /**
     * Collect clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html
     *
     * @return QueryBuilder
     */
    public function collect(string|array $variableName = null, string $expression = null): self
    {
        $groups = [];
        if (is_string($variableName)) {
            $groups[0][0] = $variableName;
            $groups[0][1] = $expression;
        }
        if (is_array($variableName)) {
            $groups =  $variableName;
        }

        $this->addCommand(new CollectClause($groups));

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
    public function into($groupsVariable, $projectionExpression = null): self
    {
        $this->addCommand(new IntoClause($groupsVariable, $projectionExpression));

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
    public function keep($keepVariable): self
    {
        $this->addCommand(new KeepClause($keepVariable));

        return $this;
    }

    /**
     * withCount clause
     * Count the collected and grouped data.
     * withCount can only be used after a into clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#group-length-calculation
     *
     * @param $countVariableName
     *
     * @return QueryBuilder
     */
    public function withCount($countVariableName): self
    {
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
    public function aggregate($variableName, $aggregateExpression): self
    {
        $this->addCommand(new AggregateClause($variableName, $aggregateExpression));

        return $this;
    }

    /**
     * Sort documents to return.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-sort.html
     *
     * @param  mixed  ...$references
     * @return QueryBuilder
     */
    public function sort(...$references): self
    {
        $this->addCommand(new SortClause($references));

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
    public function limit(int $offsetOrCount, int $count = null): self
    {
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
    public function return($expression, $distinct = false): self
    {
        $this->addCommand(new ReturnClause($expression, $distinct));

        return $this;
    }

    public function options($options): self
    {
        $this->addCommand(new OptionsClause($options));

        return $this;
    }
}
