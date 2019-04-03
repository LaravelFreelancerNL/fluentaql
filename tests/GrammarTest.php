<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;
use LaravelFreelancerNL\FluentAQL\Grammar;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Grammar
 */
class GrammarTest extends TestCase
{

    /**
     * All of the available predicate operators.
     *
     * @var Grammar
     */
    protected $grammar;

    /**
     *
     */
    public function setUp() : void
    {
        $this->grammar = new Grammar();
    }


    /**
     * normalize argument
     * @test
     */
    function normalize_argument()
    {
        $result = $this->grammar->normalizeArgument(['col`1'], ['list', 'query', 'literal']);
        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\ListExpression::class, $result);

        $result = $this->grammar->normalizeArgument('col`1', ['list', 'query', 'literal']);
        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression::class, $result);

        $result = $this->grammar->normalizeArgument('1..2', ['list', 'query', 'literal', 'range']);
        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\RangeExpression::class, $result);

        $result = $this->grammar->normalizeArgument(AQB::for('u')->in('users')->return('u'), ['list', 'query', 'literal', 'range']);
        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\QueryExpression::class, $result);
    }

    /**
     * is document
     * @test
     */
    function is_document()
    {
        $result = $this->grammar->is_document('{ "test": "is this a document?" }');
        self::assertTrue($result);

        $result = $this->grammar->is_document('{ "this is not a document" } .');
        self::assertFalse($result);

        $result = $this->grammar->is_document((object)[]);
        self::assertTrue($result);
    }

    /**
     * is range
     * @test
     */
    function is_range()
    {
        $result = $this->grammar->is_range('0..1');
        self::assertTrue($result);

        $result = $this->grammar->is_range('2..1');
        self::assertTrue($result);

        $result = $this->grammar->is_range('00..111');
        self::assertTrue($result);

        $result = $this->grammar->is_range('0.1..1');
        self::assertTrue($result);

        $result = $this->grammar->is_range('0.1.');
        self::assertFalse($result);

        $result = $this->grammar->is_range('0..1.');
        self::assertFalse($result);

        $result = $this->grammar->is_range('a..1');
        self::assertFalse($result);

    }

    /**
     * is legal variable name
     * @test
     */
    function validate_variable_name_syntax()
    {
        $result = $this->grammar->is_variable('doc');
        self::assertTrue($result);

        $result = $this->grammar->is_variable('dOc');
        self::assertTrue($result);

        $result = $this->grammar->is_variable('Doc0');
        self::assertTrue($result);

        $result = $this->grammar->is_variable('_doc');
        self::assertTrue($result);

        $result = $this->grammar->is_variable('$doc');
        self::assertTrue($result);

        $result = $this->grammar->is_variable('$$doc');
        self::assertFalse($result);

        $result = $this->grammar->is_variable('$doc$');
        self::assertFalse($result);

        $result = $this->grammar->is_variable('doc-eat-dog');
        self::assertFalse($result);

        $result = $this->grammar->is_variable('-doc');
        self::assertFalse($result);

        $result = $this->grammar->is_variable('d"oc');
        self::assertFalse($result);

        $result = $this->grammar->is_variable('döc');
        self::assertFalse($result);
    }

    /**
     * validate collection name syntax
     * @test
     */
    function validate_collection_name_syntax()
    {
        $result = $this->grammar->validateCollectionNameSyntax('col');
        self::assertTrue($result);

        $result = $this->grammar->validateCollectionNameSyntax('_col');
        self::assertTrue($result);

        $result = $this->grammar->validateCollectionNameSyntax('c_ol');
        self::assertTrue($result);

        $result = $this->grammar->validateCollectionNameSyntax('co-l');
        self::assertTrue($result);

        $result = $this->grammar->validateCollectionNameSyntax('col-');
        self::assertTrue($result);

        $result = $this->grammar->validateCollectionNameSyntax('col-1');
        self::assertTrue($result);

        $result = $this->grammar->validateCollectionNameSyntax('@col-1');
        self::assertFalse($result);

        $result = $this->grammar->validateCollectionNameSyntax('colö');
        self::assertFalse($result);

        $result = $this->grammar->validateCollectionNameSyntax('col.1');
        self::assertFalse($result);

        $result = $this->grammar->validateCollectionNameSyntax('col`1');
        self::assertFalse($result);

    }

    /**
     * is list
     * @test
     */
    function is_list()
    {
        $result = $this->grammar->is_list([1, 2, 3]);
        self::assertTrue($result);

        $result = $this->grammar->is_list(1);
        self::assertFalse($result);

        $result = $this->grammar->is_list('a string');
        self::assertFalse($result);
    }

}