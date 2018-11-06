<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\ParsedCommand;

class ParsedCommandTest extends TestCase
{
    public function testGet()
    {
        $parsedCommand = new ParsedCommand('test', ['test']);

        $this->assertEquals('test', $parsedCommand->getCommand());
        $this->assertEquals(['test'], $parsedCommand->getArguments());
    }
}
