<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\ParameterStrategy;
use WildPHP\Commands\ProcessedCommand;

class ProcessedCommandTest extends TestCase
{
    // mock
    public function foo()
    {
    }
    public function testGetApplicableStrategy()
    {
        $parameterStrategy = new ParameterStrategy();
        $processedCommand = new ProcessedCommand('test', ['test'], $parameterStrategy, ['ing'], [$this, 'foo']);

        self::assertSame($parameterStrategy, $processedCommand->getApplicableStrategy());
        self::assertSame(['ing'], $processedCommand->getConvertedParameters());
        self::assertSame([$this, 'foo'], $processedCommand->getCallback());
    }
}
