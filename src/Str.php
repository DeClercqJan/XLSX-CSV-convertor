<?php

namespace App;

class Str
{

    private $str;

    public function __construct(string $str)
    {
        $this->str = $str;
    }

    public function __toString()
    {
        return $this->str;
    }

    public function concat($str)
    {
        return new static($this->str . ' ' . $str);
    }
}
