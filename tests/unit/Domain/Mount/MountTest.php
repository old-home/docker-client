<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Mount;

use Graywings\DockerClient\Domain\Mount\Mount;
use Graywings\DockerClient\Domain\Mount\MountType;
use Graywings\DockerClient\Domain\Mount\StorageDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the `Mount` class, which represents
 * a Docker mount configuration. It validates the initialization of `Mount`
 * objects with various properties, such as type, name, source, destination,
 * driver, mode, read/write status, and propagation settings.
 */
#[CoversClass(Mount::class)]
final class MountTest extends TestCase
{
    /**
     * Tests the initialization of a `Mount` object.
     *
     * This test ensures that a `Mount` object can be created with specific
     * values for its properties, such as type, name, source, destination,
     * driver, mode, read/write status, and propagation settings. It also
     * verifies that these properties are correctly assigned and accessible.
     */
    public function testMountInitialization(): void
    {
        $mount = new Mount(
            type: MountType::Bind,
            name: 'test_mount',
            source: '/source/path',
            destination: '/destination/path',
            driver: StorageDriver::Overlay2,
            mode: 'rw',
            rw: true,
            propagation: 'rprivate',
        );

        $this->assertSame(MountType::Bind, $mount->type);
        $this->assertSame('test_mount', $mount->name);
        $this->assertSame('/source/path', $mount->source);
        $this->assertSame('/destination/path', $mount->destination);
        $this->assertSame(StorageDriver::Overlay2, $mount->driver);
        $this->assertSame('rw', $mount->mode);
        $this->assertTrue($mount->rw);
        $this->assertSame('rprivate', $mount->propagation);
    }
}
