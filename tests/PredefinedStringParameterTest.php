<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Parameters\PredefinedStringParameter;

class PredefinedStringParameterTest extends TestCase
{
    public function testPredefinedString(): void
    {
        $predefinedStringParameter = new PredefinedStringParameter('test');

        $this->assertTrue($predefinedStringParameter->validate('test'));
        $this->assertFalse($predefinedStringParameter->validate('Test'));
        $this->assertFalse($predefinedStringParameter->validate('ing'));
    }
}
