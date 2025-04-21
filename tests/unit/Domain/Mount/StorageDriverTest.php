<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Mount;

use Graywings\DockerClient\Domain\Mount\StorageDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * This class contains unit tests for the `StorageDriver` enum, which represents
 * various Docker storage drivers. It validates the enum values, ensures correct
 * behavior when converting from strings, and verifies that invalid storage driver
 * names throw appropriate exceptions.
 */
#[CoversClass(StorageDriver::class)]
final class StorageDriverTest extends TestCase
{
    /**
     * Tests the values of the `StorageDriver` enum.
     *
     * This test ensures that the enum values for each storage driver
     * (e.g., overlay2, btrfs, zfs) are correctly defined and match
     * the expected string representations.
     */
    public function testStorageDriverValues(): void
    {
        $this->assertSame('overlay2', StorageDriver::Overlay2->value);
        $this->assertSame('fuse-overlayfs', StorageDriver::FuseOverlayfs->value);
        $this->assertSame('btrfs', StorageDriver::Btrfs->value);
        $this->assertSame('zfs', StorageDriver::Zfs->value);
        $this->assertSame('vfs', StorageDriver::Vfs->value);
    }

    /**
     * Tests converting strings to `StorageDriver` enum values.
     *
     * This test ensures that valid string representations of storage drivers
     * can be correctly converted to their corresponding `StorageDriver` enum
     * values using the `from` method.
     */
    public function testStorageDriverFromString(): void
    {
        $this->assertSame(StorageDriver::Overlay2, StorageDriver::from('overlay2'));
        $this->assertSame(StorageDriver::Zfs, StorageDriver::from('zfs'));
    }

    /**
     * Tests that an invalid storage driver string throws an exception.
     *
     * This test ensures that attempting to convert an invalid string to a
     * `StorageDriver` enum value results in a `ValueError` exception.
     */
    public function testInvalidStorageDriver(): void
    {
        $this->expectException(ValueError::class);
        StorageDriver::from('invalid_driver');
    }
}
