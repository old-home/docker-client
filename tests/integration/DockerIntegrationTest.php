<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Integration;

use Graywings\DockerClient\Domain\Container\Container;
use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Domain\Container\Labels;
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
use Graywings\DockerClient\Domain\Network\Uri;
use Graywings\DockerClient\Infrastructures\Factories\Container\ContainerFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function file_exists;

/**
 * DockerIntegrationTests
 *
 * This class contains integration tests for the Docker engine API.
 * use the Docker CLI to interact with the Docker daemon.
 */
#[CoversClass(CIDRBlock::class)]
#[CoversClass(ContainerFactory::class)]
#[CoversClass(Containers::class)]
#[CoversClass(Container::class)]
#[CoversClass(DriverOptions::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(Labels::class)]
#[CoversClass(MacAddress::class)]
#[CoversClass(Mounts::class)]
#[CoversClass(NetworkSetting::class)]
#[CoversClass(NetworkSettings::class)]
#[CoversClass(Port::class)]
#[CoversClass(PortNumber::class)]
#[CoversClass(Ports::class)]
#[CoversClass(Uri::class)]
final class DockerIntegrationTest extends TestCase
{
    private IContainerRepository $repository;

    /**
     * If Docker socket is existing, run the test.
     * Otherwise, skip the test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $socketExists = file_exists('/var/run/docker.sock');
        if (! $socketExists) {
            $this->markTestSkipped('Docker socket not found. Integration tests require access to Docker daemon.');
        }

        $this->repository = new ContainerFactory();
    }

    /**
     * It is able to get the list of containers.
     */
    public function testGetContainer(): void
    {
        $containers = $this->repository->getContainers();

        $this->assertInstanceOf(Containers::class, $containers);
    }
}
