<?php
namespace LaravelFreelancerNL\FluentAQL\API;

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
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasStatementClauses
{
    /**
     * Assign a value to a variable.
     * @link https://www.arangodb.com/docs/3.4/aql/operations-let.html
     *
     * @param $variableName
     * @param $expression
     * @return $this
     */
    public function let($variableName, $expression)
    {
        $variableName = $this->normalizeArgument($variableName, 'variable');
        $expression = $this->normalizeArgument($expression, ['query', 'list', 'range', 'numeric', 'bind']);

        $this->addCommand(new LetClause($variableName, $expression));

        return $this;
    }

    /**
     * Insert a document in a collection
     * @link https://www.arangodb.com/docs/3.4/aql/operations-insert.html
     *
     * @param $document
     * @param string $collection
     * @return QueryBuilder
     */
    public function insert($document, string $collection) : QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);

        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new InsertClause($document, $collection));

        return $this;
    }

    /**
     * Update a document in a collection with the supplied data
     * @link https://www.arangodb.com/docs/3.4/aql/operations-update.html
     *
     * @param $document
     * @param $with
     * @param $collection
     * @return QueryBuilder
     */
    public function update($document, $with, $collection) : QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $with = $this->normalizeArgument($with, ['numeric', 'bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new UpdateClause($document, $with, $collection));

        return $this;
    }

    /**
     * Replace a document in a collection with the supplied data
     * @link https://www.arangodb.com/docs/3.4/aql/operations-replace.html
     *
     * @param $document
     * @param $with
     * @param $collection
     * @return QueryBuilder
     */
    public function replace($document, $with, string $collection) : QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $with = $this->normalizeArgument($with, ['numeric', 'bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new ReplaceClause($document, $with, $collection));

        return $this;
    }

    /**
     * Update, replace or insert documents in a collection with the supplied data.
     * @link https://www.arangodb.com/docs/3.4/aql/operations-upsert.html
     *
     * @param mixed $search
     * @param mixed $insert
     * @param mixed $with
     * @param string $collection
     * @param bool $replace
     * @return QueryBuilder
     */
    public function upsert($search, $insert, $with, string $collection, bool $replace = false) : QueryBuilder
    {
        $search = $this->normalizeArgument($search, ['key', 'variable', 'bind']);
        $insert = $this->normalizeArgument($insert, ['key', 'variable', 'bind']);
        $with = $this->normalizeArgument($with, ['numeric', 'bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new UpsertClause($search, $insert, $with, $collection, $replace));

        return $this;
    }

    /**
     * Remove a document from a collection
     * @link https://www.arangodb.com/docs/3.4/aql/operations-remove.html
     *
     * @param mixed $document
     * @param string $collection
     * @return QueryBuilder
     */
    public function remove($document, string $collection) : QueryBuilder
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $this->registerCollections($collection);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new RemoveClause($document, $collection));

        return $this;
    }
}
