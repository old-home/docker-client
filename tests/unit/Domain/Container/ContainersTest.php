<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\Container;
use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\ContainerState;
use Graywings\DockerClient\Domain\Container\Labels;
use Graywings\DockerClient\Domain\Mount\Mount;
use Graywings\DockerClient\Domain\Mount\Mounts;
use Graywings\DockerClient\Domain\Network\CIDRBlock;
use Graywings\DockerClient\Domain\Network\DriverOptions;
use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\MacAddress;
use Graywings\DockerClient\Domain\Network\NetworkSetting;
use Graywings\DockerClient\Domain\Network\NetworkSettings;
use Graywings\DockerClient\Domain\Network\Port;
use Graywings\DockerClient\Domain\Network\Ports;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Collection\Collection;

/**
 * This class contains unit tests for the `Containers` class, which represents
 * a collection of Docker containers. It validates the creation of `Containers`
 * objects from collections and JSON data, and ensures that the properties of
 * the contained `Container` objects are correctly assigned and accessible.
 */
#[CoversClass(Container::class)]
#[CoversClass(Containers::class)]
#[CoversClass(ContainerState::class)]
#[CoversClass(CIDRBlock::class)]
#[CoversClass(DriverOptions::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(Labels::class)]
#[CoversClass(MacAddress::class)]
#[CoversClass(Mounts::class)]
#[CoversClass(NetworkSettings::class)]
#[CoversClass(NetworkSetting::class)]
#[CoversClass(Ports::class)]
final class ContainersTest extends TestCase
{
    /**
     * Tests creating a `Containers` object from a `Collection` of `Container` objects.
     *
     * This test ensures that a `Containers` object can be instantiated with a
     * `Collection` of `Container` objects and that the contained objects are
     * correctly stored and accessible.
     */
    public function testCreateContainersFromCollection(): void
    {
        $container1 = new Container(
            'container1',
            ['container1'],
            'image1',
            'image1-id',
            'command1',
            1234567890,
            new Ports(new Collection(Port::class, [])),
            new Labels([]),
            ContainerState::Running,
            'Status 0',
            new Mounts(new Collection(Mount::class, [])),
            new NetworkSettings(new Collection(NetworkSetting::class, [])),
        );
        $container2 = new Container(
            'container2',
            ['container2'],
            'image2',
            'image2-id',
            'command2',
            1234567891,
            new Ports(new Collection(Port::class, [])),
            new Labels([]),
            ContainerState::Exited,
            'Status 1',
            new Mounts(new Collection(Mount::class, [])),
            new NetworkSettings(new Collection(NetworkSetting::class, [])),
        );

        $collection = new Collection(Container::class, [$container1, $container2]);
        $containers = new Containers($collection);

        $this->assertCount(2, $containers->values);
        $this->assertSame($container1, $containers->values[0]);
        $this->assertSame($container2, $containers->values[1]);
    }

    /**
     * Tests creating a `Containers` object from a JSON string.
     *
     * This test ensures that a `Containers` object can be created from a JSON
     * string representing an array of container data, and that the properties
     * of the contained `Container` objects are correctly assigned and accessible.
     */
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
                "Status":"Status 0",
                "Mounts":[],
                "NetworkSettings": {
                    "Networks": {
                        "dns-network": {
                            "IPAMConfig": null,
                            "Links": [],
                            "MacAddress": "02:1B:8B:00:00:02",
                            "Aliases": [],
                            "DriverOpts": [],
                            "GwPriority": 10,
                            "NetworkID": "network1",
                            "EndpointID": "endpoint1",
                            "Gateway": "192.168.0.1",
                            "IPAddress": "192.168.0.100",
                            "IPPrefixLen": 24,
                            "IPv6Gateway": "2001:db8:85a3:0:0:8a2e:370:7334",
                            "GlobalIPv6Address": "2001:db8:85a3:0:0:8a2e:370:7320",
                            "GlobalIPv6PrefixLen": 32,
                            "DNSNames": []
                        }
                    }
                }
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
                "Status":"Status 1",
                "Mounts":[],
                "NetworkSettings": {
                    "Networks": {
                        "dns-network": {
                            "IPAMConfig": null,
                            "Links": [],
                            "MacAddress": "02:1B:8B:00:00:01",
                            "Aliases": [],
                            "DriverOpts": [],
                            "GwPriority": 10,
                            "NetworkID": "network2",
                            "EndpointID": "endpoint2",
                            "Gateway": "192.168.0.1",
                            "IPAddress": "192.168.0.100",
                            "IPPrefixLen": 24,
                            "IPv6Gateway": "2001:db8:85a3:0:0:8a2e:370:7334",
                            "GlobalIPv6Address": "2001:db8:85a3:0:0:8a2e:370:7320",
                            "GlobalIPv6PrefixLen": 32,
                            "DNSNames": []
                        }
                    }
                }
            }
        ]
JSON_END;

        $containers = Containers::fromJson($json);

        $this->assertCount(2, $containers->values);
        $this->assertSame('container1', $containers->values[0]->id);
        $this->assertInstanceOf(Labels::class, $containers->values[0]->labels);
        $this->assertSame(ContainerState::Running, $containers->values[0]->state);
        $this->assertSame('container2', $containers->values[1]->id);
        $this->assertInstanceOf(Labels::class, $containers->values[1]->labels);
        $this->assertSame(ContainerState::Exited, $containers->values[1]->state);
    }

    /**
     * Tests creating an empty `Containers` object.
     *
     * This test ensures that a `Containers` object can be instantiated with
     * an empty `Collection` and that the `values` property is an empty array.
     */
    public function testEmptyContainers(): void
    {
        $containers = new Containers(new Collection(Container::class, []));
        $this->assertCount(0, $containers->values);
    }
}
