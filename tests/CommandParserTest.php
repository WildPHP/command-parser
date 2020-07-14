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
use WildPHP\Commands\CommandParser;
use WildPHP\Commands\Exceptions\NoApplicableStrategiesException;
use WildPHP\Commands\Exceptions\ParseException;
use WildPHP\Commands\Parameters\NumericParameter;
use WildPHP\Commands\ParameterStrategy;
use WildPHP\Commands\ParsedCommand;

class CommandParserTest extends TestCase
{
    // mock function used for building commands.
    public function foo()
    {
    }

    public function testFindApplicableStrategy()
    {
        $parameterStrategy = new ParameterStrategy(1, 1, [
            new NumericParameter()
        ]);
        $command = new Command([$this, 'foo'], [$parameterStrategy]);

        self::assertSame($parameterStrategy, CommandParser::findApplicableStrategy($command, ['1']));

        $parameterStrategy = new ParameterStrategy(1, 1, [
            new MockRejectAllParameter()
        ]);
        $command = new Command([$this, 'foo'], [$parameterStrategy]);

        $this->expectException(NoApplicableStrategiesException::class);
        CommandParser::findApplicableStrategy($command, ['test']);
    }

    public function testParseFromString()
    {
        $string = '!test param1';

        $expected = new ParsedCommand('test', ['param1']);

        self::assertEquals($expected, CommandParser::parseFromString($string, '!'));
    }

    public function testParseFromStringFirstPartIsPrefix()
    {
        $string = '! test param1';

        $this->expectException(ParseException::class);
        CommandParser::parseFromString($string, '!');
    }

    public function testParseFromStringNoPrefix()
    {
        $string = 'test param1';

        $this->expectException(ParseException::class);
        CommandParser::parseFromString($string, '!');
    }
}
