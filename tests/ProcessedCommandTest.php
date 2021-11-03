<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\ParameterStrategy;
use WildPHP\Commands\ProcessedCommand;

class ProcessedCommandTest extends TestCase
{
    // mock
    public function foo(): void
    {
    }
    public function testGetApplicableStrategy(): void
    {
        $parameterStrategy = new ParameterStrategy();
        $processedCommand = new ProcessedCommand('test', ['test'], $parameterStrategy, ['ing'], [$this, 'foo']);

        $this->assertSame($parameterStrategy, $processedCommand->getApplicableStrategy());
        $this->assertSame(['ing'], $processedCommand->getConvertedParameters());
        $this->assertSame([$this, 'foo'], $processedCommand->getCallback());
    }
}
