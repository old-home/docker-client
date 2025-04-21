<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Mock;

use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Queries\Container\ContainersQuery;
use Override;
use RuntimeException;

use function file_get_contents;

/**
 * This class provides a mock implementation of the `IContainerRepository` interface.
 * It reads Docker container data from a JSON file, parses it, and converts the data
 * into `Container` objects. The repository allows access to the parsed containers
 * through the `getContainers` method.
 */
final class JsonContainerRepository implements IContainerRepository
{
    /**
     * @var Containers An array of `Container` objects parsed from the JSON file.
     */
    private Containers $containers;

    /**
     * This constructor reads a JSON file containing Docker container data,
     * parses it, and initializes the repository with `Container` objects.
     *
     * @param string $jsonPath The path to the JSON file containing container data.
     *
     * @throws RuntimeException If the JSON file cannot be read or parsed.
     */
    public function __construct(string $jsonPath)
    {
        $jsonContent = file_get_contents($jsonPath);
        if ($jsonContent === false) {
            throw new RuntimeException('Failed to read JSON file: ' . $jsonPath);
        }

        $this->containers = Containers::fromJson($jsonContent);
    }

    /**
     * Retrieves the list of containers.
     *
     * This method returns a `Containers` object containing all the `Container`
     * objects parsed from the JSON file.
     *
     * @return Containers A collection of `Container` objects.
     */
    #[Override]
    public function getContainers(ContainersQuery|null $query = null): Containers
    {
        return $this->containers;
    }
}
