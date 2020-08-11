<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\InsertClause;
use LaravelFreelancerNL\FluentAQL\Clauses\LetClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RemoveClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReplaceClause;
use LaravelFreelancerNL\FluentAQL\Clauses\UpdateClause;
use LaravelFreelancerNL\FluentAQL\Clauses\UpsertClause;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasStatementClauses
 * API calls to add data modification commands to the builder.
 */
trait HasStatementClauses
{
    /**
     * Assign a value to a variable.
     * @link https://www.arangodb.com/docs/stable/aql/operations-let.html
     *
     * @param $variableName
     * @param $expression
     * @return $this
     */
    public function let($variableName, $expression)
    {
        $variableName = $this->normalizeArgument($variableName, 'Variable');
        $this->registerVariable($variableName);

        $expression = $this->normalizeArgument($expression, ['List', 'Object', 'Query', 'Range', 'Number', 'Bind']);

        $this->addCommand(new LetClause($variableName, $expression));

        return $this;
    }

    /**
     * Insert a document in a collection.
     * @link https://www.arangodb.com/docs/stable/aql/operations-insert.html
     *
     * @param $document
     * @param string $collection
     * @return QueryBuilder
     */
    public function insert($document, string $collection): QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['RegisteredVariable', 'Object', 'Bind']);

        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['Collection', 'Bind']);

        $this->addCommand(new InsertClause($document, $collection));

        return $this;
    }

    /**
     * Update a document in a collection with the supplied data.
     * @link https://www.arangodb.com/docs/stable/aql/operations-update.html
     *
     * @param $document
     * @param $with
     * @param $collection
     * @return QueryBuilder
     */
    public function update($document, $with, $collection): QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['RegisteredVariable', 'Key', 'Object', 'Bind']);
        $with = $this->normalizeArgument($with, ['Object', 'Bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['Collection', 'Bind']);

        $this->addCommand(new UpdateClause($document, $with, $collection));

        return $this;
    }

    /**
     * Replace a document in a collection with the supplied data.
     * @link https://www.arangodb.com/docs/stable/aql/operations-replace.html
     *
     * @param $document
     * @param $with
     * @param $collection
     * @return QueryBuilder
     */
    public function replace($document, $with, string $collection): QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['RegisteredVariable', 'Key', 'Object', 'Bind']);
        $with = $this->normalizeArgument($with, ['Object', 'Bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['Collection', 'Bind']);

        $this->addCommand(new ReplaceClause($document, $with, $collection));

        return $this;
    }

    /**
     * Update, replace or insert documents in a collection with the supplied data.
     * @link https://www.arangodb.com/docs/stable/aql/operations-upsert.html
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param mixed $search
     * @param mixed $insert
     * @param mixed $with
     * @param string $collection
     * @param bool $replace
     * @return QueryBuilder
     */
    public function upsert($search, $insert, $with, string $collection, bool $replace = false): QueryBuilder
    {
        $search = $this->normalizeArgument($search, ['RegisteredVariable', 'Key', 'Bind']);
        $insert = $this->normalizeArgument($insert, ['RegisteredVariable', 'Key', 'Bind']);
        $with = $this->normalizeArgument($with, ['Object', 'Bind']);
        $collection = $this->normalizeArgument($collection, ['Collection', 'Bind']);
        $this->registerCollections($collection);

        $this->addCommand(new UpsertClause($search, $insert, $with, $collection, $replace));

        return $this;
    }

    /**
     * Remove a document from a collection.
     * @link https://www.arangodb.com/docs/stable/aql/operations-remove.html
     *
     * @param mixed $document
     * @param string $collection
     * @return QueryBuilder
     */
    public function remove($document, string $collection): QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['RegisteredVariable', 'Key', 'Object', 'Bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['Collection', 'Bind']);

        $this->addCommand(new RemoveClause($document, $collection));

        return $this;
    }
}
