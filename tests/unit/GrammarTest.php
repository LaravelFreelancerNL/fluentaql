<?php

use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\RangeExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;
use LaravelFreelancerNL\FluentAQL\Grammar;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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
     * is range
     * @test
     */
    public function is_range()
    {
        $result = $this->grammar->isRange('0..1');
        self::assertTrue($result);

        $result = $this->grammar->isRange('2..1');
        self::assertTrue($result);

        $result = $this->grammar->isRange('00..111');
        self::assertTrue($result);

        $result = $this->grammar->isRange('0.1..1');
        self::assertTrue($result);

        $result = $this->grammar->isRange('0.1.');
        self::assertFalse($result);

        $result = $this->grammar->isRange('0..1.');
        self::assertFalse($result);

        $result = $this->grammar->isRange('a..1');
        self::assertFalse($result);
    }

    /**
     * validate collection name syntax
     * @test
     */
    public function is_collection()
    {
        $result = $this->grammar->isCollection('col');
        self::assertTrue($result);

        $result = $this->grammar->isCollection('_col');
        self::assertTrue($result);

        $result = $this->grammar->isCollection('c_ol');
        self::assertTrue($result);

        $result = $this->grammar->isCollection('co-l');
        self::assertTrue($result);

        $result = $this->grammar->isCollection('col-');
        self::assertTrue($result);

        $result = $this->grammar->isCollection('col-1');
        self::assertTrue($result);

        $result = $this->grammar->isCollection('@col-1');
        self::assertFalse($result);

        $result = $this->grammar->isCollection('colö');
        self::assertFalse($result);

        $result = $this->grammar->isCollection('col.1');
        self::assertFalse($result);

        $result = $this->grammar->isCollection('col`1');
        self::assertFalse($result);
    }

    /**
     * is key
     * @test
     */
    public function is_key()
    {
        $result = $this->grammar->isKey('_key');
        self::assertTrue($result);

        $result = $this->grammar->isKey('_key');
        self::assertTrue($result);

        $result = $this->grammar->isKey('100');
        self::assertTrue($result);

        $result = $this->grammar->isKey('Aݔ');
        self::assertFalse($result);

        $result = $this->grammar->isKey('Aä');
        self::assertFalse($result);
    }

    /**
     * is id
     * @test
     */
    public function is_id()
    {
        $result = $this->grammar->isId('Characters/BranStark');
        self::assertTrue($result);

        $result = $this->grammar->isId('col-1/Aa');
        self::assertTrue($result);

        $result = $this->grammar->isId('col/_key');
        self::assertTrue($result);

        $result = $this->grammar->isId('col1-1/100');
        self::assertTrue($result);

        $result = $this->grammar->isId('@col-1/_key');
        self::assertFalse($result);

        $result = $this->grammar->isId('col/Aä');
        self::assertFalse($result);
    }

    /**
     * is legal variable name
     * @test
     */
    public function validate_variable_name_syntax()
    {
        $result = $this->grammar->isVariable('doc');
        self::assertTrue($result);

        $result = $this->grammar->isVariable('dOc');
        self::assertTrue($result);

        $result = $this->grammar->isVariable('Doc0');
        self::assertTrue($result);

        $result = $this->grammar->isVariable('_doc');
        self::assertTrue($result);

        $result = $this->grammar->isVariable('$doc');
        self::assertTrue($result);

        $result = $this->grammar->isVariable('$$doc');
        self::assertFalse($result);

        $result = $this->grammar->isVariable('$doc$');
        self::assertFalse($result);

        $result = $this->grammar->isVariable('doc-eat-dog');
        self::assertFalse($result);

        $result = $this->grammar->isVariable('-doc');
        self::assertFalse($result);

        $result = $this->grammar->isVariable('d"oc');
        self::assertFalse($result);

        $result = $this->grammar->isVariable('döc');
        self::assertFalse($result);
    }

    /**
     * is attribute
     * @test
     */
    public function is_attribute()
    {
        $result = $this->grammar->isAttribute('`_key`');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('@shizzle');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('shizzle%');
        self::assertFalse($result);

        $result = $this->grammar->isAttribute('@shizzle%');
        self::assertFalse($result);

        $result = $this->grammar->isAttribute('`FOR`[whatever]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('`KEYWORD`[whatever][*][*]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('`KEYWORD`[`.fgdfg.`]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('`KEYWORD`[whatever][*][*].what');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('_from[*]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('_from[**]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('_key[2]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('_from[*][**]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.active');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.@active');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.@active.age');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.@active.@age');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.`@active`');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.`@active`.@age');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('doc.text[2]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('doc.@text[2]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('doc[name]');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('attributes[*].name');
        self::assertTrue($result);

        $result = $this->grammar->isAttribute('u.friends[*][*].name');
        self::assertTrue($result);
    }

    /**
     * is variableAttribute
     * @test
     */
    public function is_variable_attribute()
    {
        $registeredVariables = ['doc', 'u'];

        $result = $this->grammar->isVariableAttribute('doc[name]', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isVariableAttribute('u.[*]', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isVariableAttribute('smurf[name]', $registeredVariables);
        self::assertFalse($result);

        $result = $this->grammar->isVariableAttribute('NEW[name]', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isVariableAttribute('OLD.attribute', $registeredVariables);
        self::assertTrue($result);
    }


    /**
     * is document
     * @test
     */
    public function is_document()
    {
        $doc = new stdClass();
        $doc->attribute1 = 'test';
        $result = $this->grammar->isObject($doc);
        self::assertTrue($result);

        $associativeArray = [
            'attribute1' => 'test'
        ];
        $result = $this->grammar->isObject($associativeArray);
        self::assertTrue($result);

        $listArray = ['test'];
        $result = $this->grammar->isObject($listArray);
        self::assertFalse($result);
    }

    /**
     * is list
     * @test
     */
    public function is_list()
    {
        $result = $this->grammar->isList([1, 2, 3]);
        self::assertTrue($result);

        $result = $this->grammar->isList(1);
        self::assertFalse($result);

        $result = $this->grammar->isList('a string');
        self::assertFalse($result);
    }

    /**
     * is_number
     * @test
     */
    public function is_numeric()
    {
        $result = $this->grammar->isNumber(4);
        self::assertTrue($result);

        $result = $this->grammar->isNumber(4.4);
        self::assertTrue($result);

        $result = $this->grammar->isNumber('string');
        self::assertFalse($result);
    }

    /**
     * formatBind
     * @test
     */
    public function format_bind()
    {
        $result = $this->grammar->formatBind('aBindName');
        self::assertEquals('@aBindName', $result);

        $result = $this->grammar->formatBind('@aBindName');
        self::assertEquals('@`@aBindName`', $result);

        $result = $this->grammar->formatBind('aCollection', true);
        self::assertEquals('@@aCollection', $result);
    }

    /**
     * validateBindParameterSyntax
     * @test
     */
    public function validate_bind_parameter_syntax()
    {
        $result = $this->grammar->validateBindParameterSyntax('aBindVariableName');
        self::assertTrue($result);

        $result = $this->grammar->validateBindParameterSyntax('@aBindVariableName');
        self::assertTrue($result);

        $result = $this->grammar->validateBindParameterSyntax('a-faultybind-variable-name');
        self::assertFalse($result);

        $result = $this->grammar->validateBindParameterSyntax('@@aBindVariableName');
        self::assertFalse($result);
    }

    /**
     * is sort direction
     * @test
     */
    public function is_sort_direction()
    {
        $result = $this->grammar->isSortDirection('asc');
        self::assertTrue($result);
        $result = $this->grammar->isSortDirection('csa');
        self::assertFalse($result);
        $result = $this->grammar->isSortDirection('aSc');
        self::assertTrue($result);
        $result = $this->grammar->isSortDirection('desc');
        self::assertTrue($result);
    }

    /**
     * is graph direction
     * @test
     */
    public function is_graph_direction()
    {
        $result = $this->grammar->isDirection('outbound');
        self::assertTrue($result);

        $result = $this->grammar->isDirection('inbound');
        self::assertTrue($result);

        $result = $this->grammar->isDirection('ANY');
        self::assertTrue($result);

        $result = $this->grammar->isDirection('dfhdrf');
        self::assertFalse($result);
    }

    /**
     * is function
     * @test
     */
    public function is_function()
    {
        $result = $this->grammar->isFunction(AQB::document('Characters/123'));
        self::assertTrue($result);
    }

    /**
     * is logical operator
     * @test
     */
    public function is_logical_operator()
    {
        $result = $this->grammar->isLogicalOperator('AND');
        self::assertTrue($result);

        $result = $this->grammar->isLogicalOperator('!');
        self::assertTrue($result);

        $result = $this->grammar->isLogicalOperator('whatever');
        self::assertFalse($result);
    }

    /**
     * is array associative or numeric
     * @test
     */
    public function is_array_associative()
    {
        $emptyArray = [];
        $numericArray = [
            0 => 'Varys',
            "1" => 'Petyr Baelish',
            '2' => 'The Onion Knight'
        ];
        $associativeArray = [
            'name' => 'Drogon',
            'race' => 'dragon',
            'color' => 'black'
        ];
        $mixedArray = [
            "name" => 'Varys',
            "01" => 'Eunuch',
            "employer" => 'The Realm'
        ];

        $result = $this->grammar->isAssociativeArray($emptyArray);
        self::assertTrue($result);

        $result = $this->grammar->isAssociativeArray($associativeArray);
        self::assertTrue($result);

        $result = $this->grammar->isAssociativeArray($mixedArray);
        self::assertTrue($result);

        $result = $this->grammar->isAssociativeArray($numericArray);
        self::assertFalse($result);
    }

    /**
     * is array numeric
     * @test
     */
    public function is_array_associative_or_numeric()
    {
        $emptyArray = [];
        $numericArray = [
            0 => 'Varys',
            "1" => 'Petyr Baelish',
            '2' => 'The Onion Knight'
        ];
        $associativeArray = [
            'name' => 'Drogon',
            'race' => 'dragon',
            'color' => 'black'
        ];
        $mixedArray = [
            "name" => 'Varys',
            "01" => 'Eunuch',
            "employer" => 'The Realm'
        ];

        $result = $this->grammar->isIndexedArray($emptyArray);
        self::assertTrue($result);

        $result = $this->grammar->isIndexedArray($associativeArray);
        self::assertFalse($result);

        $result = $this->grammar->isIndexedArray($mixedArray);
        self::assertFalse($result);

        $result = $this->grammar->isIndexedArray($numericArray);
        self::assertTrue($result);
    }
}
