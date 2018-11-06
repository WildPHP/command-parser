<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use WildPHP\Commands\ConvertibleParameterInterface;
use WildPHP\Commands\StringParameter;

class MockConvertibleParameter extends StringParameter implements ConvertibleParameterInterface
{
    /**
     * @param string $input
     * @return mixed Output may be unpredictable.
     */
    public function convert(string $input)
    {
        return 'ing';
    }
}