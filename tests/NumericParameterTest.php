<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use PHPUnit\Framework\TestCase;
use WildPHP\Commands\NumericParameter;

class NumericParameterTest extends TestCase
{

    public function testConvert()
    {
        $numericParameter = new NumericParameter();
        $this->assertSame(3, $numericParameter->convert('3'));
    }
}
