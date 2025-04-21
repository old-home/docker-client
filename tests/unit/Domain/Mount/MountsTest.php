<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Mount;

use Graywings\DockerClient\Domain\Mount\Mount;
use Graywings\DockerClient\Domain\Mount\Mounts;
use Graywings\DockerClient\Domain\Mount\MountType;
use Graywings\DockerClient\Domain\Mount\StorageDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Collection\Collection;

/**
 * This class contains unit tests for the `Mounts` class, which represents
 * a collection of Docker mount configurations. It validates the initialization
 * of `Mounts` objects with a collection of `Mount` objects and ensures that
 * the properties and methods of the `Mounts` class behave as expected.
 */
#[CoversClass(Mounts::class)]
#[CoversClass(Mount::class)]
final class MountsTest extends TestCase
{
    /**
     * Tests the initialization of a `Mounts` object.
     *
     * This test ensures that a `Mounts` object can be created with a collection
     * of `Mount` objects and that the collection is correctly stored and accessible.
     * It also verifies that the `Mounts` object correctly handles the number of
     * mounts and provides access to the first and last mounts in the collection.
     */
    public function testMountsInitialization(): void
    {
        $mount1 = new Mount(
            type: MountType::Bind,
            name: 'test_mount_1',
            source: '/source/path/1',
            destination: '/destination/path/1',
            driver: StorageDriver::Overlay2,
            mode: 'rw',
            rw: true,
            propagation: 'rprivate',
        );

        $mount2 = new Mount(
            type: MountType::Volume,
            name: 'test_mount_2',
            source: '/source/path/2',
            destination: '/destination/path/2',
            driver: StorageDriver::Btrfs,
            mode: 'ro',
            rw: false,
            propagation: 'shared',
        );

        $collection = new Collection(Mount::class, [$mount1, $mount2]);
        $mounts     = new Mounts($collection);

        $this->assertInstanceOf(Collection::class, $mounts->mounts);
        $this->assertSame(2, $mounts->mounts->count());
        $this->assertSame($mount1, $mounts->mounts->first());
        $this->assertSame($mount2, $mounts->mounts->last());
    }
}
