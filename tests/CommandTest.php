<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Command;
use WildPHP\Commands\Parameters\NumericParameter;
use WildPHP\Commands\ParameterStrategy;

class CommandTest extends TestCase
{
    // mock
    public function foo(): void
    {
    }

    public function bar(): void
    {
    }

    public function testSetCallback(): void
    {
        $parameterStrategy = new ParameterStrategy();
        $command = new Command([$this, 'foo'], $parameterStrategy);

        $command->setCallback([$this, 'bar']);
        $this->assertEquals([$this, 'bar'], $command->getCallback());
    }

    public function testGetParameterStrategies(): void
    {
        $parameterStrategies = [
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy()
        ];
        $command = new Command([$this, 'foo'], $parameterStrategies);

        $this->assertSame($parameterStrategies, $command->getParameterStrategies());
    }

    public function testGetCallback(): void
    {
        $parameterStrategy = new ParameterStrategy();
        $command = new Command([$this, 'foo'], $parameterStrategy);
        $this->assertEquals([$this, 'foo'], $command->getCallback());
    }

    public function testSetParameterStrategies(): void
    {
        $parameterStrategies = [
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy(),
            new ParameterStrategy()
        ];
        $command = new Command([$this, 'foo'], new ParameterStrategy());

        $command->setParameterStrategies($parameterStrategies);
        $this->assertSame($parameterStrategies, $command->getParameterStrategies());

        $this->expectException(InvalidArgumentException::class);
        $command->setParameterStrategies([new NumericParameter()]);
    }
}
