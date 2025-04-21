<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Feature;

use DI\Container;
use DI\ContainerBuilder;
use Graywings\DockerClient\Domain\Container\Container as DockerContainer;
use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\ContainerState;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Domain\Container\Labels;
use Graywings\DockerClient\Domain\Mount\Mount;
use Graywings\DockerClient\Domain\Mount\Mounts;
use Graywings\DockerClient\Domain\Network\CIDRBlock;
use Graywings\DockerClient\Domain\Network\DriverOptions;
use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\IPVersion;
use Graywings\DockerClient\Domain\Network\MacAddress;
use Graywings\DockerClient\Domain\Network\NetworkSetting;
use Graywings\DockerClient\Domain\Network\NetworkSettings;
use Graywings\DockerClient\Domain\Network\Port;
use Graywings\DockerClient\Domain\Network\PortNumber;
use Graywings\DockerClient\Domain\Network\Ports;
use Graywings\DockerClient\Domain\Network\TransportProtocol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This test class contains feature tests for the `ContainerRepositoryMock` class.
 * It validates the behavior of the `getContainers` method, ensuring that the
 * repository returns the expected list of containers with correct properties,
 * such as ID, names, image, status, ports, and sizes.
 */
#[CoversClass(CIDRBlock::class)]
#[CoversClass(Containers::class)]
#[CoversClass(ContainerState::class)]
#[CoversClass(DockerContainer::class)]
#[CoversClass(DriverOptions::class)]
#[CoversClass(Labels::class)]
#[CoversClass(MacAddress::class)]
#[CoversClass(Mounts::class)]
#[CoversClass(Mount::class)]
#[CoversClass(NetworkSettings::class)]
#[CoversClass(NetworkSetting::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(IPVersion::class)]
#[CoversClass(Port::class)]
#[CoversClass(PortNumber::class)]
#[CoversClass(Ports::class)]
#[CoversClass(TransportProtocol::class)]
final class ContainerRepositoryMockTest extends TestCase
{
    private Container $container;

    /**
     * Set up the Mocked ContainerGateway.
     *
     * This method initializes the DI container and sets up the mocked
     * `ContainerGateway` for testing.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../di/test.php');
        $this->container = $builder->build();
    }

    /**
     * Test the `getContainers` method of the `ContainerRepositoryMock` class.
     *
     * This test ensures that the `getContainers` method returns a list of
     * containers with the expected properties, such as ID, names, image,
     * status, ports, and sizes. It also verifies that the ports are correctly
     * mapped with the appropriate IP address, private port, public port, and
     * transport protocol.
     */
    public function testGetContainers(): void
    {
        $repository     = $this->container->get(IContainerRepository::class);
        $containers     = $repository->getContainers();
        $containerArray = $containers->values;

        $this->assertCount(1, $containerArray);

        // Validate the first container
        $this->assertSame('83923ed9636ae8734abfd31d771132efd5e3ed0ee1737be69925173cddcbf623', $containerArray[0]->id);
        $this->assertSame(['/my_dnsmasq_container'], $containerArray[0]->names);
        $this->assertSame(
            'sha256:549e868727d1995c6c7bdce88eb41de9976674b1b0573ae29dcda0fc64c075ee',
            $containerArray[0]->image,
        );
        $this->assertSame('Up 33 hours', $containerArray[0]->status);
        $this->assertSame(ContainerState::Running, $containerArray[0]->state);

        $ports = $containerArray[0]->ports->values;
        $this->assertCount(4, $ports);
        $port = $ports[0];
        $this->assertSame('0.0.0.0', (string) $port->ipAddress);
        $this->assertSame(53, $port->privatePort->value);
        $this->assertSame(53, $port->publicPort->value);
        $this->assertSame(TransportProtocol::TCP, $port->transportProtocol);
    }
}
