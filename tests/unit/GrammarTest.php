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
     * validate collection name syntax
     * @test
     */
    public function is_collection()
    {
        $result = $this->grammar->is_collection('col');
        self::assertTrue($result);

        $result = $this->grammar->is_collection('_col');
        self::assertTrue($result);

        $result = $this->grammar->is_collection('c_ol');
        self::assertTrue($result);

        $result = $this->grammar->is_collection('co-l');
        self::assertTrue($result);

        $result = $this->grammar->is_collection('col-');
        self::assertTrue($result);

        $result = $this->grammar->is_collection('col-1');
        self::assertTrue($result);

        $result = $this->grammar->is_collection('@col-1');
        self::assertFalse($result);

        $result = $this->grammar->is_collection('colö');
        self::assertFalse($result);

        $result = $this->grammar->is_collection('col.1');
        self::assertFalse($result);

        $result = $this->grammar->is_collection('col`1');
        self::assertFalse($result);
    }

    /**
     * is key
     * @test
     */
    public function is_key()
    {
        $result = $this->grammar->is_key('_key');
        self::assertTrue($result);

        $result = $this->grammar->is_key('_key');
        self::assertTrue($result);

        $result = $this->grammar->is_key('100');
        self::assertTrue($result);

        $result = $this->grammar->is_key('Aݔ');
        self::assertFalse($result);

        $result = $this->grammar->is_key('Aä');
        self::assertFalse($result);
    }

    /**
     * is id
     * @test
     */
    public function is_id()
    {
        $result = $this->grammar->is_id('Characters/BranStark');
        self::assertTrue($result);

        $result = $this->grammar->is_id('col-1/Aa');
        self::assertTrue($result);

        $result = $this->grammar->is_id('col/_key');
        self::assertTrue($result);

        $result = $this->grammar->is_id('col1-1/100');
        self::assertTrue($result);

        $result = $this->grammar->is_id('@col-1/_key');
        self::assertFalse($result);

        $result = $this->grammar->is_id('col/Aä');
        self::assertFalse($result);
    }

    /**
     * is legal variable name
     * @test
     */
    public function validate_variable_name_syntax()
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
     * is attribute
     * @test
     */
    public function is_attribute()
    {
        $result = $this->grammar->is_attribute('`_key`');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('@shizzle');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('shizzle%');
        self::assertFalse($result);

        $result = $this->grammar->is_attribute('@shizzle%');
        self::assertFalse($result);

        $result = $this->grammar->is_attribute('`FOR`[whatever]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('`KEYWORD`[whatever][*][*]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('`KEYWORD`[`.fgdfg.`]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('`KEYWORD`[whatever][*][*].what');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('_from[*]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('_from[**]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('_key[2]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('_from[*][**]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.active');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.@active');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.@active.age');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.@active.@age');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.`@active`');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.`@active`.@age');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('doc.text[2]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('doc.@text[2]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('doc[name]');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('attributes[*].name');
        self::assertTrue($result);

        $result = $this->grammar->is_attribute('u.friends[*][*].name');
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
        $result = $this->grammar->is_document($doc);
        self::assertTrue($result);

        $associativeArray = [
            'attribute1' => 'test'
        ];
        $result = $this->grammar->is_document($associativeArray);
        self::assertTrue($result);

        $listArray = ['test'];
        $result = $this->grammar->is_document($listArray);
        self::assertFalse($result);
    }

    /**
     * is list
     * @test
     */
    public function is_list()
    {
        $result = $this->grammar->is_list([1, 2, 3]);
        self::assertTrue($result);

        $result = $this->grammar->is_list(1);
        self::assertFalse($result);

        $result = $this->grammar->is_list('a string');
        self::assertFalse($result);
    }

    /**
     * is_number
     * @test
     */
    public function is_numeric()
    {
        $result = $this->grammar->is_numeric(4);
        self::assertTrue($result);

        $result = $this->grammar->is_numeric(4.4);
        self::assertTrue($result);

        $result = $this->grammar->is_numeric('string');
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
        $result = $this->grammar->is_sortDirection('asc');
        self::assertTrue($result);
        $result = $this->grammar->is_sortDirection('csa');
        self::assertFalse($result);
        $result = $this->grammar->is_sortDirection('aSc');
        self::assertTrue($result);
        $result = $this->grammar->is_sortDirection('desc');
        self::assertTrue($result);
    }

    /**
     * is graph direction
     * @test
     */
    public function is_graph_direction()
    {
        $result = $this->grammar->is_direction('outbound');
        self::assertTrue($result);

        $result = $this->grammar->is_direction('inbound');
        self::assertTrue($result);

        $result = $this->grammar->is_direction('ANY');
        self::assertTrue($result);

        $result = $this->grammar->is_direction('dfhdrf');
        self::assertFalse($result);
    }

    /**
     * is function
     * @test
     */
    public function is_function()
    {
        $result = $this->grammar->is_function(AQB::document('Characters/123'));
        self::assertTrue($result);
    }

    /**
     * is logical operator
     * @test
     */
    public function is_logical_operator()
    {
        $result = $this->grammar->is_logicalOperator('AND');
        self::assertTrue($result);

        $result = $this->grammar->is_logicalOperator('!');
        self::assertTrue($result);

        $result = $this->grammar->is_logicalOperator('whatever');
        self::assertFalse($result);
    }

    /**
     * prepareDataToBind
     * @test
     */
    public function prepare_data_to_bind()
    {
        $data = 'shizzle';
        $preparedData = $this->grammar->prepareDataToBind($data);
        self::assertEquals('shizzle', $preparedData);

        $data = 666;
        $preparedData = $this->grammar->prepareDataToBind($data);
        self::assertEquals(666, $preparedData);

        $data = [1, 2];
        $preparedData = $this->grammar->prepareDataToBind($data);
        self::assertEquals($data, $preparedData);

        $data = (object) ['locations/123-456', 'locations/234-567', 'locations/345-678'];
        $preparedData = $this->grammar->prepareDataToBind($data);
        self::assertEquals('{"0":"locations/123-456","1":"locations/234-567","2":"locations/345-678"}', $preparedData);

        $data = [1, 2, (object) [ 'attribute 1' => "One piece!!!", 'attribute 2' => "` backtick party"]];
        $preparedData = $this->grammar->prepareDataToBind($data);
        $controlData = [1, 2, '{"attribute 1":"One piece!!!","attribute 2":"` backtick party"}'];
        self::assertNotEquals($data, $preparedData);
        self::assertEquals($controlData, $preparedData);
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

        $result = $this->grammar->arrayIsAssociative($emptyArray);
        self::assertTrue($result);

        $result = $this->grammar->arrayIsAssociative($associativeArray);
        self::assertTrue($result);

        $result = $this->grammar->arrayIsAssociative($mixedArray);
        self::assertTrue($result);

        $result = $this->grammar->arrayIsAssociative($numericArray);
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

        $result = $this->grammar->arrayIsNumeric($emptyArray);
        self::assertTrue($result);

        $result = $this->grammar->arrayIsNumeric($associativeArray);
        self::assertFalse($result);

        $result = $this->grammar->arrayIsNumeric($mixedArray);
        self::assertFalse($result);

        $result = $this->grammar->arrayIsNumeric($numericArray);
        self::assertTrue($result);
    }
}
