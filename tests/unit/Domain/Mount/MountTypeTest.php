<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Mount;

use Graywings\DockerClient\Domain\Mount\MountType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * This class contains unit tests for the `MountType` enum, which represents
 * the various types of Docker mounts. It validates the enum values, ensures
 * correct behavior when converting from strings, and verifies that invalid
 * mount types throw appropriate exceptions.
 */
#[CoversClass(MountType::class)]
final class MountTypeTest extends TestCase
{
    /**
     * Tests the values of the `MountType` enum.
     *
     * This test ensures that the enum values for each mount type
     * (e.g., bind, volume, tmpfs) are correctly defined and match
     * the expected string representations.
     */
    public function testMountTypeValues(): void
    {
        $this->assertSame('bind', MountType::Bind->value);
        $this->assertSame('volume', MountType::Volume->value);
        $this->assertSame('tmpfs', MountType::Tmpfs->value);
        $this->assertSame('image', MountType::Image->value);
        $this->assertSame('npipe', MountType::Npipe->value);
        $this->assertSame('cluster', MountType::Cluster->value);
    }

    /**
     * Tests converting strings to `MountType` enum values.
     *
     * This test ensures that valid string representations of mount types
     * can be correctly converted to their corresponding `MountType` enum
     * values using the `from` method.
     */
    public function testMountTypeFromString(): void
    {
        $this->assertSame(MountType::Bind, MountType::from('bind'));
        $this->assertSame(MountType::Volume, MountType::from('volume'));
        $this->assertSame(MountType::Tmpfs, MountType::from('tmpfs'));
    }

    /**
     * Tests that an invalid mount type string throws an exception.
     *
     * This test ensures that attempting to convert an invalid string to a
     * `MountType` enum value results in a `ValueError` exception.
     */
    public function testInvalidMountType(): void
    {
        $this->expectException(ValueError::class);
        MountType::from('invalid_type');
    }
}
