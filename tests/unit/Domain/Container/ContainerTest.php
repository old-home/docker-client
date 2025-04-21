<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\Container;
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
use Graywings\DockerClient\Domain\Network\PortNumber;
use Graywings\DockerClient\Domain\Network\Ports;
use Graywings\DockerClient\Domain\Network\TransportProtocol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Collection\Collection;

/**
 * This class contains unit tests for the `Container` class, which represents
 * a Docker container. It validates the creation of `Container` objects using
 * direct instantiation and from an associative array. The tests ensure that
 * all properties, such as ID, names, image, command, state, mounts, and network
 * settings, are correctly assigned and accessible.
 */
#[CoversClass(CIDRBlock::class)]
#[CoversClass(Container::class)]
#[CoversClass(ContainerState::class)]
#[CoversClass(DriverOptions::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(Labels::class)]
#[CoversClass(MacAddress::class)]
#[CoversClass(Mounts::class)]
#[CoversClass(NetworkSettings::class)]
#[CoversClass(NetworkSetting::class)]
#[CoversClass(Port::class)]
#[CoversClass(Ports::class)]
#[CoversClass(PortNumber::class)]
final class ContainerTest extends TestCase
{
    /**
     * Tests creating a `Container` object using direct instantiation.
     *
     * This test ensures that a `Container` object can be created with specific
     * values for its properties, such as ID, names, image, command, state, mounts,
     * and network settings. It also verifies that the properties are correctly
     * assigned and accessible.
     */
    public function testCreateContainer(): void
    {
        $labels          = new Labels(['key' => 'value']);
        $networkSettings = new NetworkSettings(new Collection(NetworkSetting::class, []));
        $mounts          = new Mounts(new Collection(Mount::class, []));
        $ports           = new Ports(
            new Collection(
                Port::class,
                [
                    new Port(
                        IPAddress::parse('0.0.0.0'),
                        new PortNumber(80),
                        new PortNumber(8080),
                        TransportProtocol::TCP,
                    ),
                ],
            ),
        );
        $container       = new Container(
            'container1',
            ['container1'],
            'image1',
            'image1-id',
            'command1',
            1234567890,
            $ports,
            $labels,
            ContainerState::Running,
            'status 0',
            $mounts,
            $networkSettings,
            1024,
            2048,
        );

        $this->assertSame('container1', $container->id);
        $this->assertSame(['container1'], $container->names);
        $this->assertSame('image1', $container->image);
        $this->assertSame('image1-id', $container->imageId);
        $this->assertSame('command1', $container->command);
        $this->assertSame(1234567890, $container->created);
        $this->assertSame($ports, $container->ports);
        $this->assertSame($labels, $container->labels);
        $this->assertSame(ContainerState::Running, $container->state);
        $this->assertSame('status 0', $container->status);
        $this->assertSame($mounts, $container->mounts);
        $this->assertSame($networkSettings, $container->networkSettings);
        $this->assertSame(1024, $container->sizeRw);
        $this->assertSame(2048, $container->sizeRootFs);
    }

    /**
     * Tests creating a `Container` object from an associative array.
     *
     * This test ensures that a `Container` object can be created from an
     * associative array with properties corresponding to the container's
     * attributes. It verifies that the properties are correctly converted
     * and accessible in the resulting `Container` object.
     */
    public function testCreateContainerFromArray(): void
    {
        $array                                               = [];
        $array['Id']                                         = 'container1';
        $array['Names']                                      = ['container1'];
        $array['Image']                                      = 'image1';
        $array['ImageID']                                    = 'image1-id';
        $array['Command']                                    = 'command1';
        $array['Created']                                    = 1234567890;
        $array['Ports']                                      = [];
        $array['Labels']                                     = ['key' => 'value'];
        $array['State']                                      = 'running';
        $array['Status']                                     = 'status 0';
        $array['Mounts']                                     = [];
        $array['NetworkSettings']                            = [];
        $array['NetworkSettings']['Networks']                = [];
        $array['NetworkSettings']['Networks']['dns-network'] = [];
        $array['NetworkSettings']['Networks']['dns-network']
            ['IPAMConfig']                                   = null;
        $array['NetworkSettings']['Networks']['dns-network']
            ['Links']                                        = ['169.254.0.1'];
        $array['NetworkSettings']['Networks']['dns-network']
            ['MacAddress']                                   = '02:1B:8B:00:00:02';
        $array['NetworkSettings']['Networks']['dns-network']
            ['Aliases']                                      = [];
        $array['NetworkSettings']['Networks']['dns-network']
            ['DriverOpts']                                   = [];
        $array['NetworkSettings']['Networks']['dns-network']
            ['GwPriority']                                   = 10;
        $array['NetworkSettings']['Networks']['dns-network']
            ['NetworkID']                                    = 'network1';
        $array['NetworkSettings']['Networks']['dns-network']
            ['EndpointID']                                   = 'endpoint1';
        $array['NetworkSettings']['Networks']['dns-network']
            ['Gateway']                                      = '192.168.0.1';
        $array['NetworkSettings']['Networks']['dns-network']
            ['IPAddress']                                    = '192.168.0.100';
        $array['NetworkSettings']['Networks']['dns-network']
            ['IPPrefixLen']                                  = 24;
        $array['NetworkSettings']['Networks']['dns-network']
            ['IPv6Gateway']                                  = '2001:db8:85a3:0:0:8a2e:370:7334';
        $array['NetworkSettings']['Networks']['dns-network']
            ['GlobalIPv6Address']                            = '2001:db8:85a3:0:0:8a2e:370:7320';
        $array['NetworkSettings']['Networks']['dns-network']
            ['GlobalIPv6PrefixLen']                          = 32;
        $array['NetworkSettings']['Networks']['dns-network']
            ['DNSNames']                                     = [];
        $array['SizeRw']                                     = 1024;
        $array['SizeRootFs']                                 = 2048;

        $container = Container::fromArray($array);

        $this->assertSame('container1', $container->id);
        $this->assertSame(['container1'], $container->names);
        $this->assertSame('image1', $container->image);
        $this->assertSame('image1-id', $container->imageId);
        $this->assertSame('command1', $container->command);
        $this->assertSame(1234567890, $container->created);
        $this->assertCount(0, $container->ports->values);
        $this->assertInstanceOf(Labels::class, $container->labels);
        $this->assertSame(ContainerState::Running, $container->state);
        $this->assertSame('status 0', $container->status);
        $this->assertInstanceOf(Mounts::class, $container->mounts);
        $this->assertInstanceOf(NetworkSettings::class, $container->networkSettings);
        $this->assertSame(1024, $container->sizeRw);
        $this->assertSame(2048, $container->sizeRootFs);
    }
}
