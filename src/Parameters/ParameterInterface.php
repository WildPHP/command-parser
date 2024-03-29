<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands\Parameters;

interface ParameterInterface
{
    /**
     * @param string $input
     *
     * @return bool
     */
    public function validate(string $input): bool;
}
