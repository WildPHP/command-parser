<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Exceptions\InvalidParameterCountException;
use WildPHP\Commands\Exceptions\ValidationException;
use WildPHP\Commands\Parameters\NumericParameter;
use WildPHP\Commands\Parameters\StringParameter;
use WildPHP\Commands\ParameterStrategy;

class ParameterStrategyTest extends TestCase
{



    public function testValidateArgumentCount()
    {
        $parameterStrategy = new ParameterStrategy(0, 1);

        self::assertTrue($parameterStrategy->validateParameterCount([])); // 0
        self::assertTrue($parameterStrategy->validateParameterCount(['test'])); // 1
        self::assertFalse($parameterStrategy->validateParameterCount(['test', 'ing'])); // 2
    }

    public function testImplodeLeftoverParameters()
    {
        $parameterStrategy = new ParameterStrategy(0, 1, [], true);
        $parameters = ['test', 'ing', 'something', 'large'];

        self::assertTrue($parameterStrategy->validateParameterCount($parameters));

        self::assertSame(
            [
                'test ing something large',
            ],
            ParameterStrategy::implodeLeftoverParameters($parameters, 0)
        );

        self::assertSame(
            [
                'test',
                'ing something large',
            ],
            ParameterStrategy::implodeLeftoverParameters($parameters, 1)
        );

        self::assertSame(
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
            'test' => new NumericParameter()
        ]);

        self::assertTrue($parameterStrategy->validateParameter('test', 1));
        self::assertFalse($parameterStrategy->validateParameter('test', 'ing'));
    }

    public function testRemapNumericParameterIndexes()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new NumericParameter(),
            'ing' => new NumericParameter()
        ]);

        $parameters = [1, 2];
        $expected = ['test' => 1, 'ing' => 2];
        self::assertEquals($expected, $parameterStrategy->remapNumericParameterIndexes($parameters));

        $parameters = [1, 'foo' => 2];
        $expected = ['test' => 1, 'foo' => 2];
        self::assertEquals($expected, $parameterStrategy->remapNumericParameterIndexes($parameters));
    }

    public function testConvertParameter()
    {
        $parameterStrategy = new ParameterStrategy(1, 4, [
            new MockConvertibleParameter(),
            new MockConvertibleParameter(),
            new MockConvertibleParameter(),
            new MockConvertibleParameter()
        ]);

        $parameters = ['test', 'test', 'test', 'test'];

        self::assertEquals(
            'ing',
            $parameterStrategy->convertParameter(1, 'test')
        );

        self::assertEquals(
            ['ing', 'ing', 'ing', 'ing'],
            $parameterStrategy->convertParameterArray($parameters)
        );

        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new StringParameter()
        ]);

        self::assertEquals(
            ['test' => 'test'],
            $parameterStrategy->convertParameterArray(['test' => 'test'])
        );

        self::assertEquals(
            'test',
            $parameterStrategy->convertParameter('test', 'test')
        );

        $this->expectException(InvalidArgumentException::class);
        $parameterStrategy->convertParameter('testing', 'ing');
    }

    public function testValidateParameterArray()
    {
        $parameterStrategy = new ParameterStrategy(3, 3, [
            'test' => new NumericParameter(),
            'test2' => new NumericParameter(),
            'test3' => new StringParameter()
        ]);

        self::assertTrue($parameterStrategy->validateParameterArray([1, 2, 3]));
        self::assertTrue($parameterStrategy->validateParameterArray([1, 2, 'test']));
        self::assertFalse($parameterStrategy->validateParameterArray(['test', 2, 3]));

        $parameterStrategy->setConcatLeftover(true);

        // implode on [3, 4] == '3 4'
        self::assertTrue($parameterStrategy->validateParameterArray([1, 2, 3, 4]));
        self::assertTrue($parameterStrategy->validateParameterArray([1, 2, 'test', 'ing']));
    }

    public function testInvalidParameterCount()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new MockConvertibleParameter()
        ]);

        $this->expectException(InvalidParameterCountException::class);
        $parameterStrategy->validateParameterArray([1, 2, 3]);
    }

    public function testMinMaxParameters()
    {
        new ParameterStrategy(3, -1);
        new ParameterStrategy(0, 0);
        new ParameterStrategy(1, 2);
        $this->expectException(InvalidArgumentException::class);
        new ParameterStrategy(3, 1);
    }

    public function testInvalidParameterName()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            'test' => new MockConvertibleParameter()
        ]);

        $this->expectException(ValidationException::class);
        $parameterStrategy->validateParameter('testing', 'test');
    }
}
