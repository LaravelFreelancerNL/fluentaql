<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\AQL\HasFunctions;
use LaravelFreelancerNL\FluentAQL\AQL\HasGraphClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasOperatorExpressions;
use LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasStatementClauses;
use LaravelFreelancerNL\FluentAQL\AQL\HasSupportCommands;
use LaravelFreelancerNL\FluentAQL\Clauses\Clause;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Traits\CompilesPredicates;
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
    use CompilesPredicates;
    use HasQueryClauses;
    use HasStatementClauses;
    use HasGraphClauses;
    use HasFunctions;
    use HasOperatorExpressions;
    use HasSupportCommands;

    /**
     * The database query grammar instance.
     *
     */
    public Grammar $grammar;

    /**
     * The AQL query.
     */
    public ?string $query = null;

    /**
     * Bindings for $query.
     *
     * @var array<mixed> $binds
     */
    public array $binds = [];

    /**
     * List of read/write/exclusive collections required for transactions.
     *
     * @var array<mixed> $collections
     */
    public array $collections = [];

    /**
     * List of Clauses to be compiled into a query.
     *
     * @var array<mixed> $commands
     */
    protected array $commands = [];

    /**
     * Registry of variable names used in this query.
     *
     * @var array<mixed> $variables
     */
    protected array $variables = [];

    /**
     * ID of the query
     * Used as prefix for automatically generated bindings.
     */
    protected int $queryId = 1;

    public function __construct()
    {
        $this->grammar = new Grammar();

        $this->queryId = spl_object_id($this);
    }

    /**
     * Add an AQL command (raw AQL and Clauses.
     *
     * @param Clause|Expression|QueryBuilder $command
     */
    public function addCommand($command): void
    {
        $this->commands[] = $command;
    }

    /**
     * Get the clause list.
     */
    public function getCommands(): mixed
    {
        return $this->commands;
    }

    /**
     * Get the last, or a specific, command.
     */
    public function getCommand(int $index = null): mixed
    {
        if ($index === null) {
            return end($this->commands);
        }

        return $this->commands[$index];
    }

    /**
     * Remove the last, or the specified, Command.
     */
    public function removeCommand(int $index = null): bool
    {
        if ($index === null) {
            return (bool)array_pop($this->commands);
        }
        if (isset($this->commands[$index])) {
            unset($this->commands[$index]);

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
     * @param string|array<mixed>|object $variableName
     */
    public function registerVariable(
        string|array|object $variableName
    ): self {
        if ($variableName instanceof ExpressionInterface) {
            $variableName = $variableName->compile($this);
        }
        if (is_string($variableName)) {
            $variableName = [$variableName => $variableName];
        }

        if (is_array($variableName)) {
            $this->variables = array_unique(array_merge($this->variables, $variableName));
        }

        return $this;
    }

    /**
     * Bind data to a variable.
     *
     * @param object|array<mixed>|string|int|float|bool|null $data
     * @throws BindException
     */
    public function bind(
        object|array|string|int|float|bool|null $data,
        string $to = null
    ): BindExpression {
        $this->validateBindVariable($to);

        $to = $this->generateBindVariable($to);

        $this->binds[$to] = $data;

        $to = $this->grammar->formatBind($to, false);

        return new BindExpression($to, $data);
    }

    /**
     * @param array<array-key, array<array-key, mixed>|object|scalar|null> $array
     * @throws BindException
     */
    protected function bindArrayValues(array $array): void
    {
        foreach ($array as $key => $value) {
            $to = null;
            if (is_string($key)) {
                $to = $key;
            }
            $this->bind($value, $to);
        }
    }

    /**
     * Bind a collection name to a variable.
     *
     * @throws BindException
     */
    public function bindCollection(
        mixed $data,
        string $to = null
    ): BindExpression {
        $this->validateBindVariable($to);

        $to = $this->generateBindVariable($to);

        $this->binds[$to] = $data;

        $to = $this->grammar->formatBind($to, true);

        return new BindExpression($to);
    }

    /**
     * @throws BindException
     */
    protected function validateBindVariable(?string $to): void
    {
        if (isset($to) && !$this->grammar->isBindParameter($to)) {
            throw new BindException('Invalid bind parameter.');
        }
    }

    protected function generateBindVariable(?string $to): string
    {
        if ($to == null) {
            $to = $this->queryId . '_' . (count($this->binds) + 1);
        }
        return $to;
    }


    /**
     * Compile the query with its bindings and collection list.
     */
    public function compile(): self
    {
        $this->query = '';
        /** @var Expression|Clause @command */
        foreach ($this->commands as $command) {
            $this->query .= ' ' . $command->compile($this);
        }
        $this->query = trim($this->query);

        return $this;
    }

    public function get(): static
    {
        $this->compile();

        return $this;
    }

    public function getQueryId(): int
    {
        return $this->queryId;
    }

    public function toAql(): string
    {
        return $this->get()->query ?: "";
    }

    public function __toString()
    {
        return $this->toAql();
    }

    /**
     * @return array<mixed>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array<mixed> $arguments
     * @return array<mixed>
     */
    public function unsetNullValues(array $arguments): array
    {
        return array_filter(
            $arguments,
            function ($value) {
                return !is_null($value);
            }
        );
    }
}
