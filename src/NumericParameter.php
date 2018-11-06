<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

use ValidationClosures\Types;

class NumericParameter extends Parameter implements ConvertibleParameterInterface
{
    /**
     * NumericParameter constructor.
     */
    public function __construct()
    {
        parent::__construct(Types::int());
    }

    public function convert(string $input)
    {
        return $input;
    }
}