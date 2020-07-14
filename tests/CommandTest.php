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
use WildPHP\Commands\Command;
use WildPHP\Commands\Parameters\NumericParameter;
use WildPHP\Commands\ParameterStrategy;

class CommandTest extends TestCase
{
    // mock
    public function foo()
    {
    }

    public function bar()
    {
    }

    public function testSetCallback()
    {
        $parameterStrategy = new ParameterStrategy();
        $command = new Command([$this, 'foo'], $parameterStrategy);

        $command->setCallback([$this, 'bar']);
        self::assertEquals([$this, 'bar'], $command->getCallback());
    }

    public function testGetParameterStrategies()
    {
        $parameterStrategies = [
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy()
        ];
        $command = new Command([$this, 'foo'], $parameterStrategies);

        self::assertSame($parameterStrategies, $command->getParameterStrategies());
    }

    public function testGetCallback()
    {
        $parameterStrategy = new ParameterStrategy();
        $command = new Command([$this, 'foo'], $parameterStrategy);
        self::assertEquals([$this, 'foo'], $command->getCallback());
    }

    public function testSetParameterStrategies()
    {
        $parameterStrategies = [
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy()
        ];
        $command = new Command([$this, 'foo'], new ParameterStrategy());

        $command->setParameterStrategies($parameterStrategies);
        self::assertSame($parameterStrategies, $command->getParameterStrategies());

        $this->expectException(InvalidArgumentException::class);
        $command->setParameterStrategies([new NumericParameter()]);
    }
}
