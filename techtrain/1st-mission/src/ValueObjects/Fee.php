<?php

namespace Src\ValueObjects;

/**
 * 料金class
 */
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

    /**
     * 料金の加算
     * @param int $value
     * @return void
     */
    public function increment($value)
    {
        $this->value += $value;
    }
}
