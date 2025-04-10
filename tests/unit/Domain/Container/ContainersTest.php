<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\Container;
use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\ContainerState;
use Graywings\DockerClient\Domain\Container\Labels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Collection\Collection;

#[CoversClass(Containers::class)]
#[CoversClass(Container::class)]
#[CoversClass(Labels::class)]
#[CoversClass(ContainerState::class)]
final class ContainersTest extends TestCase
{
    public function testCreateContainersFromCollection(): void
    {
        $container1 = new Container(
            'container1',
            ['container1'],
            'image1',
            'image1-id',
            'command1',
            1234567890,
            [],
            new Labels([]),
            ContainerState::Running,
            'Status 0',
        );
        $container2 = new Container(
            'container2',
            ['container2'],
            'image2',
            'image2-id',
            'command2',
            1234567891,
            [],
            new Labels([]),
            ContainerState::Exited,
            'Status 1',
        );

        $collection = new Collection(Container::class, [$container1, $container2]);
        $containers = new Containers($collection);

        $this->assertCount(2, $containers->containers);
        $this->assertEquals($container1, $containers->containers[0]);
        $this->assertEquals($container2, $containers->containers[1]);
    }

    public function testCreateContainersFromJson(): void
    {
        $json = <<<'JSON_END'
        [
            {
                "Id":"container1",
                "Names":["container1"],
                "Image":"image1",
                "ImageID":"image1-id",
                "Command":"command1",
                "Created":1234567890,
                "Ports":[],
                "Labels":{},
                "State":"running",
                "Status":"Status 0"
            },
            {
                "Id":"container2",
                "Names":["container2"],
                "Image":"image2",
                "ImageID":"image2-id",
                "Command":"command2",
                "Created":1234567891,
                "Ports":[],
                "Labels":{},
                "State":"exited",
                "Status":"Status 1"
             }
        ]
JSON_END;

        $containers = Containers::fromJson($json);

        $this->assertCount(2, $containers->containers);
        $this->assertEquals('container1', $containers->containers[0]->id);
        $this->assertInstanceOf(Labels::class, $containers->containers[0]->labels);
        $this->assertEquals(ContainerState::Running, $containers->containers[0]->state);
        $this->assertEquals('container2', $containers->containers[1]->id);
        $this->assertInstanceOf(Labels::class, $containers->containers[1]->labels);
        $this->assertEquals(ContainerState::Exited, $containers->containers[1]->state);
    }

    public function testEmptyContainers(): void
    {
        $containers = new Containers(new Collection(Container::class, []));
        $this->assertCount(0, $containers->containers);
    }
}
