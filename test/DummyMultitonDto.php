<?php

namespace Vectorial1024\MultitonDto\Test;

use Vectorial1024\MultitonDto\MultitonDtoTrait;

class DummyMultitonDto
{
    use MultitonDtoTrait;

    public function __construct(
        public readonly int $theValue
    ) {
    }

    protected function provideDtoId(): string
    {
        return (string) $this->theValue;
    }
}
