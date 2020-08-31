<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Grammar;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Grammar
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\ValidatesExpressions
 */
class GrammarTest extends TestCase
{
    /**
     * All of the available predicate operators.
     *
     * @var Grammar
     */
    protected $grammar;

    public function setUp(): void
    {
        $this->grammar = new Grammar();
    }

    public function testIsRange()
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

    public function testIsCollection()
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

    public function testIsKey()
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

    public function testisId()
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

    public function testValidateVariableNameSyntax()
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

    public function testIsAttribute()
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

    public function testIsReference()
    {
        $registeredVariables = ['doc', 'u'];

        $result = $this->grammar->isReference('doc[name]', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isReference('u.[*]', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isReference('smurf[name]', $registeredVariables);
        self::assertFalse($result);

        $result = $this->grammar->isReference('u[name]', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isReference('NEW._key', $registeredVariables);
        self::assertTrue($result);

        $result = $this->grammar->isReference('OLD.attribute', $registeredVariables);
        self::assertTrue($result);
    }

    public function testIsDocument()
    {
        $doc = new \stdClass();
        $doc->attribute1 = 'test';
        $result = $this->grammar->isObject($doc);
        self::assertTrue($result);

        $associativeArray = [
            'attribute1' => 'test',
        ];
        $result = $this->grammar->isObject($associativeArray);
        self::assertTrue($result);

        $listArray = ['test'];
        $result = $this->grammar->isObject($listArray);
        self::assertFalse($result);
    }

    public function testIsList()
    {
        $result = $this->grammar->isList([1, 2, 3]);
        self::assertTrue($result);

        $result = $this->grammar->isList(1);
        self::assertFalse($result);

        $result = $this->grammar->isList('a string');
        self::assertFalse($result);
    }

    public function testIsNumeric()
    {
        $result = $this->grammar->isNumber(4);
        self::assertTrue($result);

        $result = $this->grammar->isNumber(4.4);
        self::assertTrue($result);

        $result = $this->grammar->isNumber('string');
        self::assertFalse($result);
    }

    public function testFormatBind()
    {
        $result = $this->grammar->formatBind('aBindName');
        self::assertEquals('@aBindName', $result);

        $result = $this->grammar->formatBind('@aBindName');
        self::assertEquals('@`@aBindName`', $result);

        $result = $this->grammar->formatBind('aCollection', true);
        self::assertEquals('@@aCollection', $result);
    }

    public function testValidateBindParameterSyntax()
    {
        $result = $this->grammar->isBindParameter('aBindVariableName');
        self::assertTrue($result);

        $result = $this->grammar->isBindParameter('@aBindVariableName');
        self::assertTrue($result);

        $result = $this->grammar->isBindParameter('a-faultybind-variable-name');
        self::assertFalse($result);

        $result = $this->grammar->isBindParameter('@@aBindVariableName');
        self::assertFalse($result);
    }

    public function testIsSortDirection()
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

    public function testIsGraphDirection()
    {
        $result = $this->grammar->isGraphDirection('outbound');
        self::assertTrue($result);

        $result = $this->grammar->isGraphDirection('inbound');
        self::assertTrue($result);

        $result = $this->grammar->isGraphDirection('ANY');
        self::assertTrue($result);

        $result = $this->grammar->isGraphDirection('dfhdrf');
        self::assertFalse($result);
    }

    public function testIsFunction()
    {
        $result = $this->grammar->isFunction((new QueryBuilder())->document('Characters/123'));
        self::assertTrue($result);
    }

    public function testIsLogicalOperator()
    {
        $result = $this->grammar->isLogicalOperator('AND');
        self::assertTrue($result);

        $result = $this->grammar->isLogicalOperator('!');
        self::assertTrue($result);

        $result = $this->grammar->isLogicalOperator('whatever');
        self::assertFalse($result);
    }

    public function testIsAssociativeArray()
    {
        $emptyArray = [];
        $numericArray = [
            0   => 'Varys',
            '1' => 'Petyr Baelish',
            '2' => 'The Onion Knight',
        ];
        $associativeArray = [
            'name'  => 'Drogon',
            'race'  => 'dragon',
            'color' => 'black',
        ];
        $mixedArray = [
            'name'     => 'Varys',
            '01'       => 'Eunuch',
            'employer' => 'The Realm',
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

    public function testIsNumericArray()
    {
        $emptyArray = [];
        $numericArray = [
            0   => 'Varys',
            '1' => 'Petyr Baelish',
            '2' => 'The Onion Knight',
        ];
        $associativeArray = [
            'name'  => 'Drogon',
            'race'  => 'dragon',
            'color' => 'black',
        ];
        $mixedArray = [
            'name'     => 'Varys',
            '01'       => 'Eunuch',
            'employer' => 'The Realm',
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

    public function testGetAllowedExpressionTypes()
    {
        $defaultAllowedExpressionTypes = [
            'Number'    => 'Number',
            'Boolean'   => 'Boolean',
            'Null'      => 'Null',
            'Reference' => 'Reference',
            'Id'        => 'Id',
            'Key'       => 'Key',
            'Bind'      => 'Bind',
        ];

        $result = $this->grammar->getAllowedExpressionTypes();
        self::assertEquals($defaultAllowedExpressionTypes, $result);
    }

    public function testGetDateFormat()
    {
        $qb = new QueryBuilder();
        $result = $qb->grammar->getDateformat();
        self::assertEquals('Y-m-d\TH:i:s.v\Z', $result);
    }
}
