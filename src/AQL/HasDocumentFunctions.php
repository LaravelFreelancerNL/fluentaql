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
        string|QueryBuilder|Expression $document,
        bool|QueryBuilder|Expression $removeInternal = false,
        bool|QueryBuilder|Expression $sort = false
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
     * @param string|QueryBuilder|Expression $document
     * @param string|array|QueryBuilder|Expression $attributes
     * @return FunctionExpression
     */
    public function keepAttributes(mixed $document, mixed $attributes): FunctionExpression
    {
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $arguments = [
            "document" => $document,
            "attributes" => $attributes,
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
     * @param array|object|QueryBuilder|Expression $document
     * @param array|QueryBuilder|Expression $examples
     * @param boolean|QueryBuilder|Expression $returnIndex
     * @return FunctionExpression
     */
    public function matches(mixed $document, mixed $examples, mixed $returnIndex = false): FunctionExpression
    {
        $arguments = [
            "document" => $document,
            "examples" => $examples,
            "returnIndex" => $returnIndex,
        ];

        return new FunctionExpression('MATCHES', $arguments);
    }

    /**
     * Calculate the distance between two coordinates with the Haversine formula.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#merge
     *
     * @param  array  $documents
     * @return FunctionExpression
     */
    public function merge(...$documents): FunctionExpression
    {
        return new FunctionExpression('MERGE', $documents);
    }

    /**
     * Compare the given document against each example document provided.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#parse_identifier
     *
     * @param string|QueryBuilder|Expression $documentHandle
     * @return FunctionExpression
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
     * @param string|QueryBuilder|Expression $document
     * @param string|array|QueryBuilder|Expression $attributes
     * @return FunctionExpression
     */
    public function unset(mixed $document, mixed $attributes): FunctionExpression
    {
        if (! is_array($attributes)) {
            $attributes = [$attributes];
        }

        $arguments = [
            "document" => $document,
            "attributes" => $attributes,
        ];
        return new FunctionExpression('UNSET', $arguments);
    }
}
