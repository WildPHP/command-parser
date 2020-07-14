<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\ParsedCommand;

class ParsedCommandTest extends TestCase
{
    public function testGet()
    {
        $parsedCommand = new ParsedCommand('test', ['test']);

        self::assertEquals('test', $parsedCommand->getCommand());
        self::assertEquals(['test'], $parsedCommand->getArguments());
    }
}
