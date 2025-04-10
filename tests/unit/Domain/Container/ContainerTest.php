<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\Container;
use Graywings\DockerClient\Domain\Container\ContainerState;
use Graywings\DockerClient\Domain\Container\Labels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(Container::class)]
#[CoversClass(Labels::class)]
#[CoversClass(ContainerState::class)]
final class ContainerTest extends TestCase
{
    public function testCreateContainer(): void
    {
        $labels    = new Labels([]);
        $container = new Container(
            'container1',
            ['container1'],
            'image1',
            'image1-id',
            'command1',
            1234567890,
            [],
            $labels,
            ContainerState::Running,
            'status 0',
        );

        $this->assertSame('container1', $container->id);
        $this->assertSame(['container1'], $container->names);
        $this->assertSame('image1', $container->image);
        $this->assertSame('image1-id', $container->imageId);
        $this->assertSame('command1', $container->command);
        $this->assertSame(1234567890, $container->created);
        $this->assertEquals([], $container->ports);
        $this->assertEquals($labels, $container->labels);
        $this->assertSame(ContainerState::Running, $container->state);
    }

    public function testCreateContainerFromStdClass(): void
    {
        $stdClass          = new stdClass();
        $stdClass->Id      = 'container1';
        $stdClass->Names   = ['container1'];
        $stdClass->Image   = 'image1';
        $stdClass->ImageID = 'image1-id';
        $stdClass->Command = 'command1';
        $stdClass->Created = 1234567890;
        $stdClass->Ports   = [];
        $stdClass->Labels  = new stdClass();
        $stdClass->State   = 'running';
        $stdClass->Status  = 'Status 0';

        $container = Container::fromStdClass($stdClass);

        $this->assertEquals('container1', $container->id);
        $this->assertEquals(['container1'], $container->names);
        $this->assertEquals('image1', $container->image);
        $this->assertEquals('image1-id', $container->imageId);
        $this->assertEquals('command1', $container->command);
        $this->assertEquals(1234567890, $container->created);
        $this->assertEquals([], $container->ports);
        $this->assertInstanceOf(Labels::class, $container->labels);
        $this->assertEquals(ContainerState::Running, $container->state);
    }
}
