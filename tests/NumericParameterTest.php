<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\Parameters\NumericParameter;

class NumericParameterTest extends TestCase
{

    public function testConvert()
    {
        $numericParameter = new NumericParameter();
        self::assertSame(3, $numericParameter->convert('3'));
    }
}
