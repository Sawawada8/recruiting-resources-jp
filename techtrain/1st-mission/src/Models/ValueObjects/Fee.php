<?php

namespace Src\Models\ValueObjects;

class Fee {
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
    public function increment($value)
    {
        $this->value += $value;
    }
}
