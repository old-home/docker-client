<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Mock;

use Graywings\DockerClient\Domain\Container\Container;
use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\ContainerState;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Domain\Container\Labels;
use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\Port;
use Graywings\DockerClient\Domain\Network\PortNumber;
use Graywings\DockerClient\Domain\Network\TransportProtocol;
use Ramsey\Collection\Collection;
use RuntimeException;

use function file_get_contents;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;

use const JSON_ERROR_NONE;

final class JsonContainerRepository implements IContainerRepository
{
    /**
     * @var Container[]
     */
    private array $containers = [];

    public function __construct(string $jsonPath)
    {
        $jsonContent = file_get_contents($jsonPath);
        if ($jsonContent === false) {
            throw new RuntimeException('Failed to read JSON file: ' . $jsonPath);
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to parse JSON: ' . json_last_error_msg());
        }

        foreach ($data['containers'] as $containerData) {
            $ports = [];
            foreach ($containerData['Ports'] as $portData) {
                $ports[] = new Port(
                    IPAddress::parse($portData['IP'] ?? ''),
                    new PortNumber($portData['PrivatePort']),
                    new PortNumber($portData['PublicPort'] ?? 0),
                    TransportProtocol::from($portData['Type']),
                );
            }

            $this->containers[] = new Container(
                $containerData['Id'],
                $containerData['Names'],
                $containerData['Image'],
                $containerData['ImageID'],
                $containerData['Command'],
                $containerData['Created'],
                $ports,
                new Labels($containerData['Labels']),
                ContainerState::from($containerData['Status']),
                $containerData['Status'],
                $containerData['SizeRw'],
                $containerData['SizeRootFs'],
            );
        }
    }

    public function getContainers(): Containers
    {
        return new Containers(new Collection(Container::class, $this->containers));
    }
}
