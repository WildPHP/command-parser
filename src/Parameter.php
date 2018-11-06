<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;


class Parameter implements ParameterInterface
{
    /**
     * @var \Closure
     */
    protected $validationClosure;

    /**
     * Parameter constructor.
     *
     * @param \Closure $validationClosure
     */
    public function __construct(\Closure $validationClosure)
    {
        $this->validationClosure = $validationClosure;
    }

    /**
     * @inheritdoc
     */
    public function validate(string $input)
    {
        return ($this->validationClosure)($input);
    }
}