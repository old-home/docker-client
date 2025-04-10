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
use Graywings\DockerClient\Domain\Network\CIDRBlock;
use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\IPVersion;
use Graywings\DockerClient\Domain\Network\Port;
use Graywings\DockerClient\Domain\Network\PortNumber;
use Graywings\DockerClient\Domain\Network\TransportProtocol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Containers::class)]
#[CoversClass(DockerContainer::class)]
#[CoversClass(ContainerState::class)]
#[CoversClass(Labels::class)]
#[CoversClass(CIDRBlock::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(IPVersion::class)]
#[CoversClass(Port::class)]
#[CoversClass(PortNumber::class)]
#[CoversClass(TransportProtocol::class)]
final class ContainerRepositoryMockTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../di/test.php');
        $this->container = $builder->build();
    }

    public function testGetContainers(): void
    {
        $repository     = $this->container->get(IContainerRepository::class);
        $containers     = $repository->getContainers();
        $containerArray = $containers->toArray();

        $this->assertCount(2, $containerArray);

        $this->assertEquals('container1', $containerArray[0]->id);
        $this->assertEquals(['/test-container-1'], $containerArray[0]->names);
        $this->assertEquals('nginx:latest', $containerArray[0]->image);
        $this->assertEquals('running', $containerArray[0]->status);
        $this->assertEquals(1024, $containerArray[0]->sizeRw);
        $this->assertEquals(102400, $containerArray[0]->sizeRootFs);

        $ports = $containerArray[0]->ports;
        $this->assertCount(1, $ports);
        $port = $ports[0];
        $this->assertEquals('0.0.0.0', (string) $port->ipAddress);
        $this->assertEquals(80, $port->privatePort->value);
        $this->assertEquals(8080, $port->publicPort->value);
        $this->assertEquals(TransportProtocol::TCP, $port->transportProtocol);

        $this->assertEquals(['/test-container-2'], $containerArray[1]->names);
        $this->assertEquals('redis:latest', $containerArray[1]->image);
        $this->assertEquals('exited', $containerArray[1]->status);
        $this->assertNull($containerArray[1]->sizeRw);
        $this->assertNull($containerArray[1]->sizeRootFs);

        $this->assertCount(0, $containerArray[1]->ports);
    }
}
