<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands\Tests;

use WildPHP\Commands\Parameters\Parameter;

class MockRejectAllParameter extends Parameter
{
    public function __construct()
    {
        parent::__construct(function () {
            return false;
        });
    }
}
