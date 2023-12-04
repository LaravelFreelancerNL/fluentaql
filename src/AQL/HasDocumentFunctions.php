<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Geo AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-geo.html
 */
trait HasDocumentFunctions
{
    /**
     * Return the top-level attribute keys of the document as an array.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#attributes
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function attributes(
        string|object $document,
        bool|object $removeInternal = false,
        bool|object $sort = false
    ): FunctionExpression {
        return new FunctionExpression('ATTRIBUTES', [
            'document' => $document,
            'removeInternal' => $removeInternal,
            'sort' => $sort,
        ]);
    }

    /**
     * Keep only the defined attributes of the document.
     * All other attributes will be removed from the result.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#keep
     *
     * @param  string|array<mixed>|object  $document
     * @param  string|array<mixed>|object  $attributes
     */
    public function keepAttributes(
        string|array|object $document,
        string|array|object $attributes
    ): FunctionExpression {
        if (!is_array($attributes)) {
            $attributes = [$attributes];
        }

        $arguments = [
            'document' => $document,
            'attributes' => $attributes,
        ];

        return new FunctionExpression('KEEP', $arguments);
    }

    /**
     * Compare the given document against each example document provided.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#matches
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param  array<mixed>|object  $document
     * @param  array<mixed>|object  $examples
     */
    public function matches(
        array|object $document,
        array|object $examples,
        bool|QueryBuilder|Expression $returnIndex = false
    ): FunctionExpression {
        $arguments = [
            'document' => $document,
            'examples' => $examples,
            'returnIndex' => $returnIndex,
        ];

        return new FunctionExpression('MATCHES', $arguments);
    }

    /**
     * Merge the documents document1 to documentN into a single document.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#merge
     *
     * @param  array<mixed>|string|Expression  $documents
     */
    public function merge(string|array|Expression ...$documents): FunctionExpression
    {
        return new FunctionExpression('MERGE', $documents);
    }

    /**
     * Compare the given document against each example document provided.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#parse_identifier
     *
     * @param  string|object  $documentHandle
     */
    public function parseIdentifier(mixed $documentHandle): FunctionExpression
    {
        return new FunctionExpression('PARSE_IDENTIFIER', [$documentHandle]);
    }

    /**
     * Remove attributes from document.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#unset
     *
     * @param  string|array<mixed>|object  $attributes
     */
    public function unset(
        string|object $document,
        string|array|object $attributes
    ): FunctionExpression {
        if (!is_array($attributes)) {
            $attributes = [$attributes];
        }

        $arguments = [
            'document' => $document,
            'attributes' => $attributes,
        ];

        return new FunctionExpression('UNSET', $arguments);
    }
}
