<?php

namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\AQL\HasFunctions;
use LaravelFreelancerNL\FluentAQL\AQL\HasGraphClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasStatementClauses;
use LaravelFreelancerNL\FluentAQL\Clauses\Clause;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
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
     * List of commands to be compiled into a query.
     */
    protected $commands = [];

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

    /**
     * Total number of (sub)queries, including this one.
     *
     * @var int
     */
    protected $queryCount = 1;

    protected $isSubQuery = false;

    public function __construct()
    {
        $this->grammar = new Grammar();

        $this->queryId = spl_object_id($this);
    }

    protected function setSubQuery()
    {
        $this->isSubQuery = true;

        return $this;
    }

    /**
     * Add an AQL command (raw AQL and clauses.
     *
     * @param Clause|QueryBuilder $clause
     */
    public function addCommand($clause)
    {
        $this->commands[] = $clause;
    }

    /**
     * Get the command list.
     *
     * @return mixed
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Get the last or a specific command.
     *
     * @param int|null $index
     *
     * @return mixed
     */
    public function getCommand(int $index = null)
    {
        if ($index === null) {
            return end($this->commands);
        }

        return $this->commands[$index];
    }

    /**
     * Remove the last or a specified command.
     *
     * @param null $index
     *
     * @return bool
     */
    public function removeCommand($index = null): bool
    {
        if ($index === null) {
            return (array_pop($this->commands)) ? true : false;
        }
        if (isset($this->commands[$index])) {
            unset($this->commands[$index]);

            return true;
        }

        return false;
    }

    /**
     * Clear all commands.
     */
    public function clearCommands()
    {
        $this->commands = [];
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
     * @param string $variableName
     *
     * @return QueryBuilder
     */
    protected function registerVariable(string $variableName): self
    {
        $this->variables[$variableName] = $variableName;

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
            $to = $this->queryId.'_'.(count($this->binds) + 1);
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

        foreach ($this->commands as $command) {
            $result = $command->compile();
            $this->query .= ' '.$result;

            if ($command instanceof self) {
                // Extract binds
                $this->binds = array_unique(array_merge($this->binds, $command->binds));

                // Extract collections
                foreach ($command->collections as $mode) {
                    $this->registerCollections($command->collections[$mode], $mode);
                }
            }
        }
        $this->query = trim($this->query);

        if ($this->isSubQuery) {
            $this->query = '('.$this->query.')';
        }

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
}
