<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;

/**
 * Trait hasFunctions
 *
 * Miscellaneous function.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasMiscellaneousFunctions
{

    public function document($collection, $id = null)
    {
        if ($id === null) {
            $id = $collection;
            $collection = null;;
        }

        if ($collection !== null && $this->grammar->validateCollectionNameSyntax($collection)) {
            $arguments['collection'] = new LiteralExpression($collection);
        }
        if (is_array($id)) {
            $arguments['id'] = $this->grammar->normalizeArray($id, ['query', 'key']);
        } else {
            $arguments['id'] = $this->grammar->normalizeArgument($id, ['query', 'key']);
        }




        return new FunctionExpression('DOCUMENT', $arguments);
    }
}