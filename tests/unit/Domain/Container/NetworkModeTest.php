<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\NetworkMode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the `NetworkMode` class. It verifies the
 * behavior of the `NetworkMode` class, including its constructor, `parse` method,
 * and string representation.
 */
#[CoversClass(NetworkMode::class)]
final class NetworkModeTest extends TestCase
{
    /**
     * Tests that the `NetworkMode` object is correctly created with the "host" mode.
     */
    public function testValidHostMode(): void
    {
        $networkMode = new NetworkMode('host');
        $this->assertSame('host', $networkMode->getMode());
        $this->assertNull($networkMode->getId());
        $this->assertSame('host', (string) $networkMode);
    }

    /**
     * Tests that the `NetworkMode` object is correctly created with the "none" mode.
     */
    public function testValidNoneMode(): void
    {
        $networkMode = new NetworkMode('none');
        $this->assertSame('none', $networkMode->getMode());
        $this->assertNull($networkMode->getId());
        $this->assertSame('none', (string) $networkMode);
    }

    /**
     * Tests that the `NetworkMode` object is correctly created with the "container" mode and a valid ID.
     */
    public function testValidContainerMode(): void
    {
        $networkMode = new NetworkMode('container', '12345');
        $this->assertSame('container', $networkMode->getMode());
        $this->assertSame('12345', $networkMode->getId());
        $this->assertSame('container:12345', (string) $networkMode);
    }

    /**
     * Tests that an exception is thrown when an invalid mode is provided.
     */
    public function testInvalidModeThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid network mode: invalid');
        new NetworkMode('invalid');
    }

    /**
     * Tests that an exception is thrown when the "container" mode is used without an ID.
     */
    public function testContainerModeWithoutIdThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Container ID is required for "container" mode.');
        new NetworkMode('container');
    }

    /**
     * Tests that the `parse` method correctly parses the "host" mode.
     */
    public function testParseHostMode(): void
    {
        $networkMode = NetworkMode::parse('host');
        $this->assertSame('host', $networkMode->getMode());
        $this->assertNull($networkMode->getId());
        $this->assertSame('host', (string) $networkMode);
    }

    /**
     * Tests that the `parse` method correctly parses the "none" mode.
     */
    public function testParseNoneMode(): void
    {
        $networkMode = NetworkMode::parse('none');
        $this->assertSame('none', $networkMode->getMode());
        $this->assertNull($networkMode->getId());
        $this->assertSame('none', (string) $networkMode);
    }

    /**
     * Tests that the `parse` method correctly parses the "container" mode with a valid ID.
     */
    public function testParseContainerMode(): void
    {
        $networkMode = NetworkMode::parse('container:67890');
        $this->assertSame('container', $networkMode->getMode());
        $this->assertSame('67890', $networkMode->getId());
        $this->assertSame('container:67890', (string) $networkMode);
    }

    /**
     * Tests that an exception is thrown when an invalid mode is provided to the `parse` method.
     */
    public function testParseInvalidModeThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid network mode: invalid');
        NetworkMode::parse('invalid');
    }

    /**
     * Tests that an exception is thrown when the "container" mode is used without an ID in the `parse` method.
     */
    public function testParseContainerModeWithoutIdThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Container ID is required for "container" mode.');
        NetworkMode::parse('container:');
    }
}
