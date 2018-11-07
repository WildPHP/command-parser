<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\ProcessedCommand;

class ProcessedCommandTest extends TestCase
{
    // mock
    public function foo()
    {

    }
    public function testGetApplicableStrategy()
    {
        $parameterStrategy = new \WildPHP\Commands\ParameterStrategy();
        $processedCommand = new ProcessedCommand('test', ['test'], $parameterStrategy, ['ing'], [$this, 'foo']);

        $this->assertSame($parameterStrategy, $processedCommand->getApplicableStrategy());
        $this->assertSame(['ing'], $processedCommand->getConvertedParameters());
    }
}
