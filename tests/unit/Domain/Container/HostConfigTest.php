<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\HostConfig;
use Graywings\DockerClient\Domain\Container\NetworkMode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the `HostConfig` class. It verifies the
 * behavior of the `HostConfig` class, including its constructor and the
 * `fromArray` method.
 */
#[CoversClass(HostConfig::class)]
#[CoversClass(NetworkMode::class)]
final class HostConfigTest extends TestCase
{
    /**
     * Tests that a `HostConfig` object is correctly created with valid inputs.
     *
     * This test ensures that the constructor correctly initializes the `HostConfig`
     * object with a valid `NetworkMode` and annotations array.
     */
    public function testValidHostConfig(): void
    {
        $networkMode = new NetworkMode('host');
        $annotations = ['key1' => 'value1', 'key2' => 'value2'];

        $hostConfig = new HostConfig($networkMode, $annotations);

        $this->assertSame($networkMode, $hostConfig->networkMode);
        $this->assertSame($annotations, $hostConfig->annotations);
    }

    /**
     * Tests that the `fromArray` method correctly parses a valid associative array.
     *
     * This test ensures that the `fromArray` method correctly converts a valid
     * associative array into a `HostConfig` object, including parsing the `NetworkMode`
     * and annotations.
     */
    public function testFromArray(): void
    {
        $array                                 = [];
        $array['NetworkMode']                  = 'container:12345';
        $array['Annotations']                  = [];
        $array['Annotations']['key1']          = 'value1';
        $array['Annotations']['key2']          = [];
        $array['Annotations']['key2']['value'] = 'value2';

        $hostConfig = HostConfig::fromArray($array);

        $this->assertSame('container', $hostConfig->networkMode->getMode());
        $this->assertSame('12345', $hostConfig->networkMode->getId());
        $this->assertSame('container:12345', (string) $hostConfig->networkMode);
        $this->assertSame(['key1' => 'value1', 'key2' => ['value' => 'value2']], $hostConfig->annotations);
    }

    /**
     * Tests that the `fromArray` method throws an exception for an invalid `NetworkMode`.
     *
     * This test ensures that an `InvalidArgumentException` is thrown when the
     * `fromArray` method is called with an invalid `NetworkMode`.
     */
    public function testFromArrayInvalidNetworkMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid network mode: invalid');

        $array                = [];
        $array['NetworkMode'] = 'invalid';
        $array['Annotations'] = [];

        HostConfig::fromArray($array);
    }
}
