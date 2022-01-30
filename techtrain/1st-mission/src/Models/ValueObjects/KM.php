<?php

namespace Src\Models\ValueObjects;

class KM implements IDistance {
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
