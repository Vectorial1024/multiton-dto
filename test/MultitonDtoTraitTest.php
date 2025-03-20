<?php

namespace Vectorial1024\MultitonDto\Test;

use PHPUnit\Framework\TestCase;

class MultitonDtoTraitTest extends TestCase
{
    public function setUp(): void
    {
        DummyMultitonDto::resetMultitons();
    }

    public function testDtoInstanceSharing()
    {
        // test that DTO instances can be correctly shared, and that shared instances are strictly identical (i.e. same reference)
        $baseInstance = (new DummyMultitonDto(1))->toMultiton();
        $anotherInstance = (new DummyMultitonDto(1))->toMultiton();
        $this->assertTrue($anotherInstance === $baseInstance, "Shared multiton instances should be identical (i.e., \"===\"), but is not.");
    }

    public function testDtoDifferentInstances()
    {
        // test that DTO instances are not shared when their IDs are different
        $baseInstance = (new DummyMultitonDto(1))->toMultiton();
        $anotherInstance = (new DummyMultitonDto(2))->toMultiton();
        $this->assertFalse($anotherInstance === $baseInstance, "Different multiton instances should be different, but is not.");
    }

    public function testDtoUnsharedInstances()
    {
        // test that DTO instances are not shared when sharing is not requested
        $baseInstance = (new DummyMultitonDto(1))->toMultiton();
        $anotherInstance = new DummyMultitonDto(1);
        $this->assertFalse($anotherInstance === $baseInstance, "Unshared multiton instances should be different, but is not.");
    }

    public function testDtoInstanceResetting()
    {
        // test that, when we reset the shared instances, that previous shared instances are different from future shared instances
        $baseInstance = (new DummyMultitonDto(1))->toMultiton();
        DummyMultitonDto::resetMultitons();
        $anotherInstance = (new DummyMultitonDto(1))->toMultiton();
        $this->assertFalse($anotherInstance === $baseInstance, "Forgotten multiton instances should be different, but is not.");
    }
}
