<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Command;
use WildPHP\Commands\CommandProcessor;
use WildPHP\Commands\Exceptions\CommandNotFoundException;
use WildPHP\Commands\Parameters\NumericParameter;
use WildPHP\Commands\ParameterStrategy;
use WildPHP\Commands\ParsedCommand;
use WildPHP\Commands\ProcessedCommand;

class CommandProcessorTest extends TestCase
{
    // mock function used to create commands.
    public function foo()
    {
    }

    public function testRegisterCommand()
    {
        $command = new Command([$this, 'foo'], new ParameterStrategy());

        $commandProcessor = new CommandProcessor();
        self::assertTrue($commandProcessor->registerCommand('test', $command));
        self::assertFalse($commandProcessor->registerCommand('test', $command));

        self::assertSame($command, $commandProcessor->findCommand('test'));
    }

    public function testInvalidFindCommand()
    {
        $command = new Command([$this, 'foo'], new ParameterStrategy());

        $commandProcessor = new CommandProcessor();
        $commandProcessor->registerCommand('test', $command);

        $this->expectException(CommandNotFoundException::class);
        $commandProcessor->findCommand('ing');
    }

    public function testProcess()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            new NumericParameter()
        ]);
        $command = new Command([$this, 'foo'], [$parameterStrategy]);

        $expectedProcessedCommand = new ProcessedCommand(
            'test',
            ['1'],
            $parameterStrategy,
            [1],
            $command->getCallback()
        );

        $commandProcessor = new CommandProcessor();
        $commandProcessor->registerCommand('test', $command);

        $parsedCommand = new ParsedCommand('test', ['1']);

        self::assertEquals($expectedProcessedCommand, $commandProcessor->process($parsedCommand));
    }
}
