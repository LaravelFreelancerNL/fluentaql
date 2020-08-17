<?php

namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\AQL\HasFunctions;
use LaravelFreelancerNL\FluentAQL\AQL\HasGraphClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasStatementClauses;
use LaravelFreelancerNL\FluentAQL\Clauses\Clause;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Traits\NormalizesExpressions;

/**
 * Class QueryBuilder
 * Fluent ArangoDB AQL Query Builder.
 * Creates and compiles AQL queries. Returns all data necessary to run the query,
 * including bindings and a list of used read/write collections.
 */
class QueryBuilder
{
    use NormalizesExpressions;
    use HasQueryClauses;
    use HasStatementClauses;
    use HasGraphClauses;
    use HasFunctions;

    /**
     * The database query grammar instance.
     *
     * @var Grammar
     */
    public $grammar;

    /**
     * The AQL query.
     *
     * @var
     */
    public $query;

    /**
     * Bindings for $query.
     *
     * @var
     */
    public $binds = [];

    /**
     * List of read/write/exclusive collections required for transactions.
     *
     * @var array
     */
    public $collections;

    /**
     * List of clauses to be compiled into a query.
     */
    protected $clauses = [];

    /**
     * Registry of variable names used in this query.
     */
    protected $variables = [];

    /**
     * ID of the query
     * Used as prefix for automatically generated bindings.
     *
     * @var int
     */
    protected $queryId = 1;

    public function __construct()
    {
        $this->grammar = new Grammar();

        $this->queryId = spl_object_id($this);
    }

    /**
     * Add an AQL clause (raw AQL and clauses.
     *
     * @param Clause|QueryBuilder $clause
     */
    public function addClause($clause)
    {
        $this->clauses[] = $clause;
    }

    /**
     * Get the clause list.
     *
     * @return mixed
     */
    public function getClauses()
    {
        return $this->clauses;
    }

    /**
     * Get the last or a specific clause.
     *
     * @param int|null $index
     *
     * @return mixed
     */
    public function getClause(int $index = null)
    {
        if ($index === null) {
            return end($this->clauses);
        }

        return $this->clauses[$index];
    }

    /**
     * Remove the last or a specified clause.
     *
     * @param null $index
     *
     * @return bool
     */
    public function removeClause($index = null): bool
    {
        if ($index === null) {
            return (array_pop($this->clauses)) ? true : false;
        }
        if (isset($this->clauses[$index])) {
            unset($this->clauses[$index]);

            return true;
        }

        return false;
    }

    /**
     * @param mixed  $collections
     * @param string $mode
     *
     * @return QueryBuilder
     */
    public function registerCollections($collections, $mode = 'write'): self
    {
        if (!is_array($collections)) {
            $collections = [$collections];
        }

        $this->collections[$mode] = array_unique(array_merge($collections));

        return $this;
    }

    /**
     * Register variables on declaration for later data normalization.
     *
     * @param string|array $variableName
     *
     * @return QueryBuilder
     */
    public function registerVariable($variableName): self
    {
        if ($variableName instanceof ExpressionInterface) {
            $variableName = $variableName->compile($this);
        }
        if (is_string($variableName)) {
            $variableName = [$variableName => $variableName];
        }

        $this->variables = array_unique(array_merge($this->variables, $variableName));

        return $this;
    }

    /**
     * Bind data or a collection name to a variable.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param $data
     * @param null $to
     * @param bool $collection
     *
     * @throws BindException
     *
     * @return BindExpression
     */
    public function bind($data, $to = null, $collection = false): BindExpression
    {
        if (isset($to) && !$this->grammar->isBindParameter($to)) {
            throw new BindException('Invalid bind parameter.');
        }

        if ($to == null) {
            $to = $this->queryId . '_' . (count($this->binds) + 1);
        }

        $this->binds[$to] = $data;

        $to = $this->grammar->formatBind($to, $collection);

        return new BindExpression($to);
    }

    /**
     * Compile the query with its bindings and collection list.
     *
     * @return mixed
     */
    public function compile(): self
    {
        $this->query = '';
        foreach ($this->clauses as $clause) {
            $this->query .= ' ' . $clause->compile($this);
        }
        $this->query = trim($this->query);

        return $this;
    }

    /**
     * @return QueryBuilder $this
     */
    public function get()
    {
        $this->compile();

        return $this;
    }

    /**
     * @return QueryBuilder $this
     */
    public function getQueryId()
    {
        return $this->queryId;
    }

    /**
     * @return string
     */
    public function toAql()
    {
        return $this->get()->query;
    }

    public function __toString()
    {
        return $this->toAql();
    }

    public function wrap($value)
    {
        return $this->grammar->wrap($value);
    }

    public function getVariables()
    {
        return $this->variables;
    }
}
