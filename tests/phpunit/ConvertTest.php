<?php

use Jawira\CaseConverter\Convert;
use PHPUnit\Framework\TestCase;

/**
 * Unitary tests for \Jawira\CaseConverter\Convert
 *
 * @see https://jtreminio.com/blog/unit-testing-tutorial-part-i-introduction-to-phpunit/
 */
class ConvertTest extends TestCase
{

    /**
     * Testing \Jawira\CaseConverter\Convert::__construct
     *
     * Tests if constructor calls \Jawira\CaseConverter\Convert::load.
     *
     * @covers \Jawira\CaseConverter\Convert::__construct()
     */
    public function testConstructor()
    {
        $mock = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['detectNamingConvention'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('detectNamingConvention')
             ->with($this->equalTo('hello_world'));

        $class = new ReflectionObject($mock);
        $class->getConstructor()
              ->invoke($mock, 'hello_world');
    }

    /**
     * @covers       \Jawira\CaseConverter\Convert::isUppercaseWord()
     *
     * @param string $inputString
     * @param bool   $expectedResult
     *
     * @dataProvider isUppercaseWordProvider
     *
     * @throws \ReflectionException
     */
    public function testIsUppercaseWord(string $inputString, bool $expectedResult)
    {
        // Disabling constructor without stub methods
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods()
                     ->getMock();

        // Removing protected for analyse method
        $reflection = new ReflectionObject($stub);
        $method     = $reflection->getMethod('isUppercaseWord');
        $method->setAccessible(true);

        $output = $method->invoke($stub, $inputString);

        $this->assertSame($expectedResult, $output);
    }

    /**
     * Data provider for \Jawira\CaseConverter\Convert::isUppercaseWord()
     *
     * @return array
     */
    public function isUppercaseWordProvider()
    {
        return [
            ['X', true],
            ['YES', true],
            ['HELLO', true],
            ['', false],
            ['x', false],
            ['HELLOxWORLD', false],
            ['HELLO-WORLD', false],
            ['HELLO_WORLD', false],
            ['HelloWorld', false],
        ];
    }

    /**
     * Testing \Jawira\CaseConverter\Convert::analyse
     *
     * \Jawira\CaseConverter\Convert::analyse should return Convert::SNAKE if
     * $input contains '_'.
     *
     * @covers       \Jawira\CaseConverter\Convert::analyse()
     * @covers       \Jawira\CaseConverter\Convert::isUppercaseWord()
     * @dataProvider analyseProvider
     *
     * @param $input
     * @param $expected
     *
     * @throws \ReflectionException
     */
    public function testAnalyse($input, $expected)
    {
        // Disabling constructor without stub methods
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods()
                     ->getMock();

        // Removing protected for analyse method
        $reflection = new ReflectionObject($stub);
        $method     = $reflection->getMethod('analyse');
        $method->setAccessible(true);

        $output = $method->invoke($stub, $input);

        $this->assertSame($expected, $output);
    }

    /**
     * Provider method for \ConvertTest::testAnalyse
     *
     * @return array
     */
    public function analyseProvider()
    {
        return [
            'Dash 1'  => ['hola_mundo', Convert::STRATEGY_UNDERSCORE],
            'Dash 2'  => ['HELLO_WORLD', Convert::STRATEGY_UNDERSCORE],
            'Dash 3'  => ['HELLO', Convert::STRATEGY_UNDERSCORE],
            'Upper 1' => ['', Convert::STRATEGY_UPPERCASE],
            'Upper 3' => ['one', Convert::STRATEGY_UPPERCASE],
            'Upper 4' => ['helloWorld', Convert::STRATEGY_UPPERCASE],
            'dash 1'  => ['hello-World', Convert::STRATEGY_DASH],
        ];
    }

    /**
     * Testing
     *
     * Testing \Jawira\CaseConverter\Convert::toCamel
     *
     * This method reads \Jawira\CaseConverter\Convert::$words and returns a
     * _snake case_ string. Returned value can be in _pascal case_ format.
     *
     * @covers       \Jawira\CaseConverter\Convert::toSnake()
     *
     * @param array  $words     Words to transform to snake case
     * @param bool   $uppercase Get uppercase snake case
     * @param string $expected  Expected output
     *
     * @dataProvider toSnakeProvider
     * @throws \ReflectionException
     */
    public function testToSnake($words, $uppercase, $expected)
    {
        // Disabling constructor, without stub methods
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods()
                     ->getMock();

        // Making "words" property accessible and setting a value
        $reflection = new ReflectionObject($stub);
        $property   = $reflection->getProperty('words');
        $property->setAccessible(true);
        $property->setValue($stub, $words);

        /** @noinspection PhpUndefinedMethodInspection */
        $output = $stub->toSnake($uppercase);

        $this->assertSame($expected, $output);
    }

    /**
     * Provider for testToSnake
     */
    public function toSnakeProvider()
    {
        return [
            'One word lowercase'  => [['hello'], false, 'hello'],
            'One word uppercase'  => [['hello'], true, 'HELLO'],
            'Two words lowercase' => [['hello', 'world'], false, 'hello_world'],
            'Two words uppercase' => [['hello', 'world'], true, 'HELLO_WORLD'],
        ];
    }

    /**
     * Testing \Jawira\CaseConverter\Convert::toCamel
     *
     * This method reads \Jawira\CaseConverter\Convert::$words and returns a
     * _camel case_ string. Returned value can be in _screaming snake case_
     * format.
     *
     * @dataProvider toCamelProvider
     *
     * @covers       \Jawira\CaseConverter\Convert::toCamel()
     *
     * @param $words
     * @param $uppercase
     * @param $expected
     *
     * @throws \ReflectionException
     */
    public function testToCamel($words, $uppercase, $expected)
    {
        // Disabling constructor without stub methods
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods()
                     ->getMock();

        // Making "words" property accessible and setting a value
        $reflection = new ReflectionObject($stub);
        $property   = $reflection->getProperty('words');
        $property->setAccessible(true);
        $property->setValue($stub, $words);

        /** @noinspection PhpUndefinedMethodInspection */
        $output = $stub->toCamel($uppercase);

        $this->assertSame($expected, $output);
    }

    /**
     * Provider for \ConvertTest::testToCamel
     *
     * @return array
     */
    public function toCamelProvider()
    {
        return [
            'One word lowercase'  => [['hello'], false, 'hello'],
            'One word uppercase'  => [['hello'], true, 'Hello'],
            'Two words lowercase' => [['hello', 'world'], false, 'helloWorld'],
            'Two words uppercase' => [['hello', 'world'], true, 'HelloWorld'],
        ];
    }

    /**
     * Testing \Jawira\CaseConverter\Convert::readSnake
     *
     * This method should return all words contained in a _snake case_ string.
     *
     * @dataProvider readSnakeProvider
     *
     * @covers       \Jawira\CaseConverter\Convert::splitUnderscoreString()
     *
     * @param $snake
     * @param $expected
     *
     * @throws \ReflectionException
     */
    public function testReadSnake($snake, $expected)
    {
        // Disabling constructor
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods()
                     ->getMock();

        // making readSnake public
        $reflection = new ReflectionObject($stub);
        $method     = $reflection->getMethod('readSnake');
        $method->setAccessible(true);

        $output = $method->invoke($stub, $snake);

        // comparing arrays
        $this->assertSame($expected, $output);
    }

    /**
     * Data provider for \ConvertTest::testReadSnake
     *
     * @return array
     */
    public function readSnakeProvider()
    {
        return [
            ['hello_world', ['hello', 'world']],
            ['hello', ['hello']],
            ['_hello_world_', ['hello', 'world']],
            ['__hello__world__', ['hello', 'world']],
            ['', []],
            ['_', []],
            ['__', []],
        ];
    }

    /**
     * Testing \Jawira\CaseConverter\Convert::readCamel
     *
     * This method should return all words contained in a _camel case_ string.
     *
     * @covers       \Jawira\CaseConverter\Convert::splitUppercaseString()
     *
     * @dataProvider readCamelProvider
     *
     * @param string $camel
     * @param string $expected
     * @param string $returnValue
     *
     * @throws \ReflectionException
     */
    public function testReadCamel($camel, $expected, $returnValue)
    {
        // Disabling constructor
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['readSnake'])
                     ->getMock();

        // Setting expectation
        $stub->expects($this->once())
             ->method('readSnake')
             ->with($this->equalTo($expected))
             ->willReturn($returnValue);

        // making readSnake public
        $reflection = new ReflectionObject($stub);
        $method     = $reflection->getMethod('readCamel');
        $method->setAccessible(true);

        // $output must be the same value returned by readSnake()
        $output = $method->invoke($stub, $camel);

        $this->assertSame($returnValue, $output);
    }

    /**
     * Data provider for \ConvertTest::testReadCamel
     *
     * @return array
     */
    public function readCamelProvider()
    {
        return [
            ['HelloWorld', '_Hello_World', 'return-value'],
            ['helloWorld', 'hello_World', 'return-value'],
        ];
    }

    /**
     * Testing \Jawira\CaseConverter\Convert::load
     *
     * Load method should call \Jawira\CaseConverter\Convert::analyse and then
     * send parsed words to \Jawira\CaseConverter\Convert::$words.
     *
     * @covers       \Jawira\CaseConverter\Convert::detectNamingConvention()
     *
     * @dataProvider loadProvider
     *
     * @param $string
     * @param $detectedCase
     * @param $methodToCall
     * @param $words
     *
     * @throws \ReflectionException
     */
    public function testLoad($string, $detectedCase, $methodToCall, $words)
    {
        // Disabling constructor
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['analyse', $methodToCall])
                     ->getMock();

        // Setting expectation for Convert::analyse()
        $stub->expects($this->once())
             ->method('analyse')
             ->with($this->equalTo($string))
             ->willReturn($detectedCase);

        // Setting expectation for $methodToCall
        $stub->expects($this->once())
             ->method($methodToCall)
             ->with($this->equalTo($string))
             ->willReturn($words);

        // Changing load method visibility
        $reflection = new ReflectionObject($stub);
        $method     = $reflection->getMethod('load');
        $method->setAccessible(true);

        $returnedObject = $method->invoke($stub, $string);

        /** @noinspection PhpParamsInspection */
        $this->assertAttributeSame($words, 'words', $stub);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(Convert::class, $returnedObject);
    }

    /**
     * Provider method for \ConvertTest::testLoad
     *
     * @return array
     */
    public function loadProvider()
    {
        return [
            ['dummy-string', Convert::SNAKE, 'readSnake', ['dummy-array']],
            ['dummy-string', Convert::CAMEL, 'readCamel', ['dummy-array']],
        ];
    }

    /**
     * Testing \Jawira\CaseConverter\Convert::__toString
     *
     * Calls \Jawira\CaseConverter\Convert::toSnake or \Jawira\CaseConverter\Convert::toCamel
     * according to \Jawira\CaseConverter\Convert::$detectedCase value.
     *
     * @covers       \Jawira\CaseConverter\Convert::__toString()
     *
     * @dataProvider toStringProvider
     *
     * @param $methodToCall
     * @param $detectedCase
     * @param $returnString
     *
     * @throws \ReflectionException
     */
    public function test__toString($methodToCall, $detectedCase, $returnString)
    {
        // Disabling constructor
        $stub = $this->getMockBuilder(Convert::class)
                     ->disableOriginalConstructor()
                     ->setMethods([$methodToCall])
                     ->getMock();

        // Setting expectation for $methodToCall
        $stub->expects($this->once())
             ->method($methodToCall)
             ->willReturn($returnString);

        // Setting value to protected property
        $reflection = new ReflectionObject($stub);
        $property   = $reflection->getProperty('namingConvention');
        $property->setAccessible(true);
        $property->setValue($stub, $detectedCase);

        $toString = (string)$stub;
        $this->assertSame($returnString, $toString);
    }

    /**
     * Provider method for \ConvertTest::test__toString
     *
     * @return array
     */
    public function toStringProvider()
    {
        return [
            ['toCamel', Convert::SNAKE, 'dummy-string'],
            ['toSnake', Convert::CAMEL, 'dummy-string'],
        ];
    }
}
