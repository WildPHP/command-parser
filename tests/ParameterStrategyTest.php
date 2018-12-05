<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\ParameterStrategy;
use WildPHP\Tests\MockConvertibleParameter;

class ParameterStrategyTest extends TestCase
{



    public function testValidateArgumentCount()
    {
        $parameterStrategy = new ParameterStrategy(0, 1);

        $this->assertTrue($parameterStrategy->validateParameterCount([])); // 0
        $this->assertTrue($parameterStrategy->validateParameterCount(['test'])); // 1
        $this->assertFalse($parameterStrategy->validateParameterCount(['test', 'ing'])); // 2
    }

    public function testImplodeLeftoverParameters()
    {
        $parameterStrategy = new ParameterStrategy(0, 1, [], true);
        $parameters = ['test', 'ing', 'something', 'large'];

        $this->assertTrue($parameterStrategy->validateParameterCount($parameters));

        $this->assertSame(
            [
                'test ing something large',
            ],
            ParameterStrategy::implodeLeftoverParameters($parameters, 0)
        );

        $this->assertSame(
            [
                'test',
                'ing something large',
            ],
            ParameterStrategy::implodeLeftoverParameters($parameters, 1)
        );

        $this->assertSame(
            [
                'test',
                'ing',
                'something large',
            ],
            ParameterStrategy::implodeLeftoverParameters($parameters, 2)
        );
    }

    public function testValidateParameter()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new \WildPHP\Commands\Parameters\NumericParameter()
        ]);

        $this->assertTrue($parameterStrategy->validateParameter('test', 1));
        $this->assertFalse($parameterStrategy->validateParameter('test', 'ing'));
    }

    public function testRemapNumericParameterIndexes()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new \WildPHP\Commands\Parameters\NumericParameter(),
            'ing' => new \WildPHP\Commands\Parameters\NumericParameter()
        ]);

        $parameters = [1, 2];
        $expected = ['test' => 1, 'ing' => 2];
        $this->assertEquals($expected, $parameterStrategy->remapNumericParameterIndexes($parameters));

        $parameters = [1, 'foo' => 2];
        $expected = ['test' => 1, 'foo' => 2];
        $this->assertEquals($expected, $parameterStrategy->remapNumericParameterIndexes($parameters));
    }

    public function testConvertParameter()
    {
        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy(1, 4, [
            new MockConvertibleParameter(),
            new MockConvertibleParameter(),
            new MockConvertibleParameter(),
            new MockConvertibleParameter()
        ]);

        $parameters = ['test', 'test', 'test', 'test'];

        $this->assertEquals(
            'ing',
            $parameterStrategy->convertParameter(1, 'test')
        );

        $this->assertEquals(
            ['ing', 'ing', 'ing', 'ing'],
            $parameterStrategy->convertParameterArray($parameters)
        );

        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy(1, 1, [
            'test' => new \WildPHP\Commands\Parameters\StringParameter()
        ]);

        $this->assertEquals(
            ['test' => 'test'],
            $parameterStrategy->convertParameterArray(['test' => 'test'])
        );

        $this->assertEquals(
            'test',
            $parameterStrategy->convertParameter('test', 'test')
        );

        $this->expectException(\InvalidArgumentException::class);
        $parameterStrategy->convertParameter('testing', 'ing');
    }

    public function testValidateParameterArray()
    {
        $parameterStrategy = new ParameterStrategy(3, 3, [
            'test' => new \WildPHP\Commands\Parameters\NumericParameter(),
            'test2' => new \WildPHP\Commands\Parameters\NumericParameter(),
            'test3' => new \WildPHP\Commands\Parameters\StringParameter()
        ]);

        $this->assertTrue($parameterStrategy->validateParameterArray([1, 2, 3]));
        $this->assertTrue($parameterStrategy->validateParameterArray([1, 2, 'test']));
        $this->assertFalse($parameterStrategy->validateParameterArray(['test', 2, 3]));

        $parameterStrategy->setConcatLeftover(true);

        // implode on [3, 4] == '3 4'
        $this->assertTrue($parameterStrategy->validateParameterArray([1, 2, 3, 4]));
        $this->assertTrue($parameterStrategy->validateParameterArray([1, 2, 'test', 'ing']));
    }

    public function testInvalidParameterCount()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new MockConvertibleParameter()
        ]);

        $this->expectException(\WildPHP\Commands\Exceptions\InvalidParameterCountException::class);
        $parameterStrategy->validateParameterArray([1, 2, 3]);
    }

    public function testMinMaxParameters()
    {
        new ParameterStrategy(3, -1);
        new ParameterStrategy(0, 0);
        new ParameterStrategy(1, 2);
        $this->expectException(\InvalidArgumentException::class);
        new ParameterStrategy(3, 1);
    }

    public function testInvalidParameterName()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new MockConvertibleParameter()
        ]);

        $this->expectException(\WildPHP\Commands\Exceptions\ValidationException::class);
        $parameterStrategy->validateParameter('testing', 'test');
    }
}
