<?php

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Grammar
 */
class GrammarTest extends TestCase
{
    protected $grammar;

    public function setUp() : void
    {
        $this->grammar = new \LaravelFreelancerNL\FluentAQL\Grammar();
    }

    /**
     * is document
     * @test
     */
    function is_document()
    {
        $result = $this->grammar->is_document('{ "is this a document?" }');
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
    function check_variable_name_syntax()
    {
        $result = $this->grammar->checkVariableNameSyntax('doc');
        self::assertTrue($result);

        $result = $this->grammar->checkVariableNameSyntax('dOc');
        self::assertTrue($result);

        $result = $this->grammar->checkVariableNameSyntax('Doc0');
        self::assertTrue($result);

        $result = $this->grammar->checkVariableNameSyntax('_doc');
        self::assertTrue($result);

        $result = $this->grammar->checkVariableNameSyntax('$doc');
        self::assertTrue($result);

        $result = $this->grammar->checkVariableNameSyntax('$$doc');
        self::assertFalse($result);

        $result = $this->grammar->checkVariableNameSyntax('$doc$');
        self::assertFalse($result);

        $result = $this->grammar->checkVariableNameSyntax('doc-eat-dog');
        self::assertFalse($result);

        $result = $this->grammar->checkVariableNameSyntax('-doc');
        self::assertFalse($result);

        $result = $this->grammar->checkVariableNameSyntax('d"oc');
        self::assertFalse($result);

        $result = $this->grammar->checkVariableNameSyntax('döc');
        self::assertFalse($result);
    }

    /**
     * check collection name syntax
     * @test
     */
    function check_collection_name_syntax()
    {
        $result = $this->grammar->checkCollectionNameSyntax('col');
        self::assertTrue($result);

        $result = $this->grammar->checkCollectionNameSyntax('_col');
        self::assertTrue($result);

        $result = $this->grammar->checkCollectionNameSyntax('c_ol');
        self::assertTrue($result);

        $result = $this->grammar->checkCollectionNameSyntax('co-l');
        self::assertTrue($result);

        $result = $this->grammar->checkCollectionNameSyntax('col-');
        self::assertTrue($result);

        $result = $this->grammar->checkCollectionNameSyntax('col-1');
        self::assertTrue($result);

        $result = $this->grammar->checkCollectionNameSyntax('@col-1');
        self::assertFalse($result);

        $result = $this->grammar->checkCollectionNameSyntax('colö');
        self::assertFalse($result);

        $result = $this->grammar->checkCollectionNameSyntax('col.1');
        self::assertFalse($result);

        $result = $this->grammar->checkCollectionNameSyntax('col`1');
        self::assertFalse($result);

    }
}