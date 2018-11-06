<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\CommandParser;

class CommandParserTest extends TestCase
{
    // mock function used for building commands.
    public function foo()
    {

    }

    public function testFindApplicableStrategy()
    {
        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy(1, 1, [
            new \WildPHP\Commands\Parameters\NumericParameter()
        ]);
        $command = new \WildPHP\Commands\Command([$this, 'foo'], [$parameterStrategy]);

        $this->assertSame($parameterStrategy, CommandParser::findApplicableStrategy($command, ['1']));

        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy(1, 1, [
            new \WildPHP\Tests\MockRejectAllParameter()
        ]);
        $command = new \WildPHP\Commands\Command([$this, 'foo'], [$parameterStrategy]);

        $this->expectException(\WildPHP\Commands\Exceptions\NoApplicableStrategiesException::class);
        CommandParser::findApplicableStrategy($command, ['test']);
    }

    public function testParseFromString()
    {
        $string = '!test param1';

        $expected = new \WildPHP\Commands\ParsedCommand('test', ['param1']);

        $this->assertEquals($expected, CommandParser::parseFromString($string, '!'));
    }

    public function testParseFromStringFirstPartIsPrefix()
    {
        $string = '! test param1';

        $this->expectException(\WildPHP\Commands\Exceptions\ParseException::class);
        CommandParser::parseFromString($string, '!');
    }

    public function testParseFromStringNoPrefix()
    {
        $string = 'test param1';

        $this->expectException(\WildPHP\Commands\Exceptions\ParseException::class);
        CommandParser::parseFromString($string, '!');
    }
}
