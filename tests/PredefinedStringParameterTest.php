<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Parameters\PredefinedStringParameter;

class PredefinedStringParameterTest extends TestCase
{
    public function testPredefinedString()
    {
        $predefinedStringParameter = new PredefinedStringParameter('test');

        self::assertTrue($predefinedStringParameter->validate('test'));
        self::assertFalse($predefinedStringParameter->validate('Test'));
        self::assertFalse($predefinedStringParameter->validate('ing'));
    }
}
