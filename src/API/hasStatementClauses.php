<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\InsertClause;
use LaravelFreelancerNL\FluentAQL\Clauses\LetClause;
use LaravelFreelancerNL\FluentAQL\Clauses\RemoveClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReplaceClause;
use LaravelFreelancerNL\FluentAQL\Clauses\UpdateClause;
use LaravelFreelancerNL\FluentAQL\Clauses\UpsertClause;

/**
 * Trait hasStatementClauses
 * API calls to add data modification commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasStatementClauses
{
    public function let($variableName, $expression)
    {
        $variableName = $this->normalizeArgument($variableName, 'variable');
        $expression = $this->normalizeArgument($expression, ['query', 'list', 'range', 'numeric', 'bind']);

        $this->addCommand(new LetClause($variableName, $expression));

        return $this;
    }

    public function insert($document, $collection)
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new InsertClause($document, $collection));

        return $this;
    }

    public function update($document, $with, $collection)
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $with = $this->normalizeArgument($with, ['numeric', 'bind']);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new UpdateClause($document, $with, $collection));

        return $this;
    }

    public function replace($document, $with, $collection)
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $with = $this->normalizeArgument($with, ['numeric', 'bind']);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new ReplaceClause($document, $with, $collection));

        return $this;
    }

    public function upsert($search, $insert, $with, $collection, bool $replace = false)
    {
        $search = $this->normalizeArgument($search, ['key', 'variable', 'bind']);
        $insert = $this->normalizeArgument($insert, ['key', 'variable', 'bind']);
        $with = $this->normalizeArgument($with, ['numeric', 'bind']);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new UpsertClause($search, $insert, $with, $collection, $replace));

        return $this;
    }

    public function remove($document, $collection)
    {
        $document = $this->normalizeArgument($document, ['key', 'variable', 'bind']);
        $collection = $this->normalizeArgument($collection, ['collection', 'bind']);

        $this->addCommand(new RemoveClause($document, $collection));

        return $this;
    }
}
