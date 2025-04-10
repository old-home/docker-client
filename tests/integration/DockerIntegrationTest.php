<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Integration;

use Graywings\DockerClient\Domain\Container\Container;
use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Domain\Container\Labels;
use Graywings\DockerClient\Repositories\Container\ContainerRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function exec;
use function file_exists;
use function implode;

#[CoversClass(ContainerRepository::class)]
#[CoversClass(Containers::class)]
#[CoversClass(Container::class)]
#[CoversClass(Labels::class)]
final class DockerIntegrationTest extends TestCase
{
    private IContainerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $socketExists = file_exists('/var/run/docker.sock');
        if (! $socketExists) {
            $this->markTestSkipped('Docker socket not found. Integration tests require access to Docker daemon.');
        }

        exec('which docker', $output, $returnVar);
        $dockerPath = $output[0] ?? '';

        if ($returnVar !== 0 || empty($dockerPath)) {
            $this->markTestSkipped('Docker command not found. Integration tests require Docker CLI.');
        }

        if ($returnVar !== 0) {
            $error = implode("\n", $output);
            $this->markTestSkipped('Docker daemon is not responding. Error: ' . $error);
        }

        $this->repository = new ContainerRepository();
    }

    public function testGetContainer(): void
    {
        $containers = $this->repository->getContainers();

        $this->assertInstanceOf(Containers::class, $containers);
    }
}
