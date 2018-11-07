<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\CommandProcessor;

class CommandProcessorTest extends TestCase
{
    // mock function used to create commands.
    public function foo() {

    }

    public function testRegisterCommand()
    {
        $command = new \WildPHP\Commands\Command([$this, 'foo'], new \WildPHP\Commands\ParameterStrategy());

        $commandProcessor = new CommandProcessor();
        $this->assertTrue($commandProcessor->registerCommand('test', $command));
        $this->assertFalse($commandProcessor->registerCommand('test', $command));

        $this->assertSame($command, $commandProcessor->findCommand('test'));
    }

    public function testInvalidFindCommand()
    {
        $command = new \WildPHP\Commands\Command([$this, 'foo'], new \WildPHP\Commands\ParameterStrategy());

        $commandProcessor = new CommandProcessor();
        $commandProcessor->registerCommand('test', $command);

        $this->expectException(\WildPHP\Commands\Exceptions\CommandNotFoundException::class);
        $commandProcessor->findCommand('ing');
    }

    public function testProcess()
    {
        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy(1, 1, [
            new \WildPHP\Commands\Parameters\NumericParameter()
        ]);
        $command = new \WildPHP\Commands\Command([$this, 'foo'], [$parameterStrategy]);

        $expectedProcessedCommand = new \WildPHP\Commands\ProcessedCommand(
            'test',
            ['1'],
            $parameterStrategy,
            [1],
            $command->getCallback()
        );

        $commandProcessor = new CommandProcessor();
        $commandProcessor->registerCommand('test', $command);

        $parsedCommand = new \WildPHP\Commands\ParsedCommand('test', ['1']);

        $this->assertEquals($expectedProcessedCommand, $commandProcessor->process($parsedCommand));
    }
}
