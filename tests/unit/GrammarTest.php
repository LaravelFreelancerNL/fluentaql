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
    function is_sort_direction()
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
