<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\ContainerState;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * This class contains unit tests for the `ContainerState` enum, which represents
 * the various states a Docker container can be in. It validates the enum values,
 * ensures correct behavior when converting from strings, and verifies that invalid
 * states throw appropriate exceptions.
 */
#[CoversClass(ContainerState::class)]
final class ContainerStateTest extends TestCase
{
    /**
     * Tests the values of the `ContainerState` enum.
     *
     * This test ensures that the enum values for each container state
     * (e.g., created, running, exited) are correctly defined and match
     * the expected string representations.
     */
    public function testContainerStateValues(): void
    {
        $this->assertSame('created', ContainerState::Created->value);
        $this->assertSame('restarting', ContainerState::Restarting->value);
        $this->assertSame('running', ContainerState::Running->value);
        $this->assertSame('removing', ContainerState::Removing->value);
        $this->assertSame('paused', ContainerState::Paused->value);
        $this->assertSame('exited', ContainerState::Exited->value);
        $this->assertSame('dead', ContainerState::Dead->value);
    }

    /**
     * Tests converting strings to `ContainerState` enum values.
     *
     * This test ensures that valid string representations of container states
     * can be correctly converted to their corresponding `ContainerState` enum
     * values using the `from` method.
     */
    public function testContainerStateFromString(): void
    {
        $this->assertSame(ContainerState::Created, ContainerState::from('created'));
        $this->assertSame(ContainerState::Running, ContainerState::from('running'));
        $this->assertSame(ContainerState::Exited, ContainerState::from('exited'));
    }

    /**
     * Tests that an invalid container state string throws an exception.
     *
     * This test ensures that attempting to convert an invalid string to a
     * `ContainerState` enum value results in a `ValueError` exception.
     */
    public function testInvalidContainerState(): void
    {
        $this->expectException(ValueError::class);
        ContainerState::from('invalid_state');
    }
}
