<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\AggregateClause;
use LaravelFreelancerNL\FluentAQL\Clauses\CollectClause;
use LaravelFreelancerNL\FluentAQL\Clauses\FilterClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\IntoClause;
use LaravelFreelancerNL\FluentAQL\Clauses\KeepClause;
use LaravelFreelancerNL\FluentAQL\Clauses\LimitClause;
use LaravelFreelancerNL\FluentAQL\Clauses\OptionsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SearchClause;
use LaravelFreelancerNL\FluentAQL\Clauses\SortClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WindowClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithCountClause;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use phpDocumentor\Reflection\Types\ArrayKey;

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
     * @param string|array<mixed>|Expression $variableName
     */
    public function for(
        string|array|Expression $variableName,
        mixed $in = null
    ): self {
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
     */
    public function filter(
        mixed $leftOperand,
        string $comparisonOperator = null,
        mixed $rightOperand = null,
        string $logicalOperator = null
    ): self {
        $predicates = $leftOperand;
        if (! is_array($predicates)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addCommand(new FilterClause($predicates));

        return $this;
    }

    /**
     * Search a view.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-search.html
     */
    public function search(
        mixed $leftOperand,
        string $comparisonOperator = null,
        mixed $rightOperand = null,
        string $logicalOperator = null
    ): self {
        $predicates = $leftOperand;
        if (! is_array($predicates)) {
            $predicates = [$predicates];
        }
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
     * @param string|array<mixed>|null $variableName
     */
    public function collect(
        string|array $variableName = null,
        string $expression = null
    ): self {
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
     */
    public function into(
        string|QueryBuilder|Expression $groupsVariable,
        mixed $projectionExpression = null
    ): self {
        $this->addCommand(new IntoClause($groupsVariable, $projectionExpression));

        return $this;
    }

    /**
     * Keep clause
     * Limits the attributes of the data that is grouped.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#discarding-obsolete-variables
     */
    public function keep(
        string|QueryBuilder|Expression $keepVariable
    ): self {
        $this->addCommand(new KeepClause($keepVariable));

        return $this;
    }

    /**
     * withCount clause
     * Count the collected and grouped data.
     * withCount can only be used after an into clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#group-length-calculation
     */
    public function withCount(
        string|QueryBuilder|Expression $countVariableName
    ): self {
        $this->addCommand(new WithCountClause($countVariableName));

        return $this;
    }

    /**
     * Aggregate clause
     * Creates the INTO clause of a collect clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-collect.html#aggregation
     */
    public function aggregate(
        string|QueryBuilder|Expression $variableName,
        string|QueryBuilder|Expression $aggregateExpression
    ): self {
        $this->addCommand(new AggregateClause($variableName, $aggregateExpression));

        return $this;
    }

    /**
     * Sort documents to return.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-sort.html
     *
     * @param mixed ...$references
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
     */
    public function limit(
        int|QueryBuilder|Expression $offsetOrCount,
        int|QueryBuilder|Expression $count = null
    ): self {
        $this->addCommand(new LimitClause($offsetOrCount, $count));

        return $this;
    }

    /**
     * Return data.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-return.html
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function return(
        mixed $expression,
        bool $distinct = false
    ): self {
        $this->addCommand(new ReturnClause($expression, $distinct));

        return $this;
    }

    /**
     * @param array<mixed>|object $options
     */
    public function options(array|object $options): self
    {
        $this->addCommand(new OptionsClause($options));

        return $this;
    }

    /**
     * Aggregate adjacent documents or value ranges with a sliding window to calculate
     * running totals, rolling averages, and other statistical properties
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-window.html
     *
     * @param array<array-key, string>|object $offsets
     */
    public function window(
        array|object $offsets,
        string|QueryBuilder|Expression $rangeValue = null
    ): self {
        $this->addCommand(new WindowClause($offsets, $rangeValue));

        return $this;
    }
}
