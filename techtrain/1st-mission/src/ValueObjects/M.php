<?php

namespace Src\ValueObjects;

class M implements IDistance {
    /** @var int */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
