<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use Graywings\DockerClient\Queries\Container\ContainersQuery;

/**
 * This interface defines the contract for a container repository, which is
 * responsible for retrieving Docker container data. Implementations of this
 * interface provide methods to interact with the underlying data source,
 * such as the Docker API or a mock repository, to fetch container information.
 */
interface IContainerRepository
{
    /**
     * Retrieves a collection of Docker containers.
     *
     * This method is responsible for fetching container data and returning it
     * as a `Containers` object, which encapsulates a collection of `Container`
     * instances. The implementation may vary depending on the data source
     * (e.g., Docker API, mock data, or a database).
     *
     * @return Containers A collection of Docker containers.
     */
    public function getContainers(ContainersQuery|null $query): Containers;
}
