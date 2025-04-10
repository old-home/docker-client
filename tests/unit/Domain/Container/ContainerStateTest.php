<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\ContainerState;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ValueError;

#[CoversClass(ContainerState::class)]
final class ContainerStateTest extends TestCase
{
    public function testContainerStateValues(): void
    {
        $this->assertEquals('created', ContainerState::Created->value);
        $this->assertEquals('restarting', ContainerState::Restarting->value);
        $this->assertEquals('running', ContainerState::Running->value);
        $this->assertEquals('removing', ContainerState::Removing->value);
        $this->assertEquals('paused', ContainerState::Paused->value);
        $this->assertEquals('exited', ContainerState::Exited->value);
        $this->assertEquals('dead', ContainerState::Dead->value);
    }

    public function testContainerStateFromString(): void
    {
        $this->assertEquals(ContainerState::Created, ContainerState::from('created'));
        $this->assertEquals(ContainerState::Running, ContainerState::from('running'));
        $this->assertEquals(ContainerState::Exited, ContainerState::from('exited'));
    }

    public function testInvalidContainerState(): void
    {
        $this->expectException(ValueError::class);
        ContainerState::from('invalid_state');
    }
}
