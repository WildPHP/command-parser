<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Command;

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
        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy();
        $command = new Command([$this, 'foo'], $parameterStrategy);

        $command->setCallback([$this, 'bar']);
        $this->assertEquals([$this, 'bar'], $command->getCallback());
    }

    public function testGetParameterStrategies()
    {
        $parameterStrategies = [
            new \WildPHP\Commands\ParameterStrategy(),
            new \WildPHP\Commands\ParameterStrategy(),
            new \WildPHP\Commands\ParameterStrategy(),
            new \WildPHP\Commands\ParameterStrategy()
        ];
        $command = new Command([$this, 'foo'], $parameterStrategies);

        $this->assertSame($parameterStrategies, $command->getParameterStrategies());
    }

    public function testGetCallback()
    {
        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy();
        $command = new Command([$this, 'foo'], $parameterStrategy);
        $this->assertEquals([$this, 'foo'], $command->getCallback());
    }

    public function testSetParameterStrategies()
    {
        $parameterStrategies = [
            new \WildPHP\Commands\ParameterStrategy(),
            new \WildPHP\Commands\ParameterStrategy(),
            new \WildPHP\Commands\ParameterStrategy(),
            new \WildPHP\Commands\ParameterStrategy()
        ];
        $command = new Command([$this, 'foo'], new \WildPHP\Commands\ParameterStrategy());

        $command->setParameterStrategies($parameterStrategies);
        $this->assertSame($parameterStrategies, $command->getParameterStrategies());

        $this->expectException(\InvalidArgumentException::class);
        $command->setParameterStrategies([new \WildPHP\Commands\Parameters\NumericParameter()]);
    }
}
