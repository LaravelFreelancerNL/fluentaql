<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\InsertClause;
use LaravelFreelancerNL\FluentAQL\Clauses\LetClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RemoveClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReplaceClause;
use LaravelFreelancerNL\FluentAQL\Clauses\UpdateClause;
use LaravelFreelancerNL\FluentAQL\Clauses\UpsertClause;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasStatementClauses
 * API calls to add data modification commands to the builder.
 */
trait HasStatementClauses
{
    abstract public function addCommand($command);

    /**
     * Assign a value to a variable.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-let.html
     *
     * @param  array<mixed>|string|QueryBuilder|Expression  $expression
     */
    public function let(
        string $variableName,
        mixed $expression
    ): self {
        $this->addCommand(new LetClause($variableName, $expression));

        return $this;
    }

    /**
     * Insert a document in a collection.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-insert.html
     *
     * @param  array<mixed>|object|string  $document
     */
    public function insert(
        array|object|string $document,
        string|QueryBuilder|Expression $collection
    ): self {
        $this->addCommand(new InsertClause($document, $collection));

        return $this;
    }

    /**
     * Update a document in a collection with the supplied data.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-update.html
     *
     * @param  array<mixed>|string|object  $document
     * @param  array<mixed>|string|object  $with
     */
    public function update(
        array|string|object $document,
        array|string|object $with,
        string|QueryBuilder|Expression $collection
    ): self {
        $this->addCommand(new UpdateClause($document, $with, $collection));

        return $this;
    }

    /**
     * Replace a document in a collection with the supplied data.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-replace.html
     *
     * @param  array<mixed>|object|string  $document
     * @param  array<mixed>|object|string  $with
     */
    public function replace(
        array|object|string $document,
        array|object|string $with,
        string|QueryBuilder|Expression $collection
    ): self {
        $this->addCommand(new ReplaceClause($document, $with, $collection));

        return $this;
    }

    /**
     * Update, replace or insert documents in a collection with the supplied data.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-upsert.html
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param  array<mixed>|object|string  $search
     * @param  array<mixed>|object|string  $insert
     * @param  array<mixed>|object|string  $update
     */
    public function upsert(
        array|object|string $search,
        array|object|string $insert,
        array|object|string $update,
        string|QueryBuilder|Expression $collection,
        bool $replace = false
    ): self {
        $this->addCommand(new UpsertClause($search, $insert, $update, $collection, $replace));

        return $this;
    }

    /**
     * Remove a document from a collection.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-remove.html
     *
     * @param  array<mixed>|string|object  $document
     */
    public function remove(
        array|object|string $document,
        string|QueryBuilder|Expression $collection
    ): self {
        $this->addCommand(new RemoveClause($document, $collection));

        return $this;
    }
}
