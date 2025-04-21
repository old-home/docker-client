<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Infrastructures\Factories\Container;

use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Exceptions\Exception;
use Graywings\DockerClient\Infrastructures\DockerEngineApiGateway;
use Graywings\DockerClient\Queries\Container\ContainersQuery;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use JsonException;
use Override;

/**
 * This class provides an implementation of the `IContainerRepository` interface
 * for interacting with Docker containers. It uses HTTP requests to communicate
 * with the Docker API and retrieve container data.
 *
 * The repository extends the `HttpGateway` class, which provides shared
 * functionality for HTTP-based repositories.
 */
final class ContainerFactory extends DockerEngineApiGateway implements IContainerRepository
{
    /**
     * Retrieves a list of Docker containers.
     *
     * This method sends an HTTP GET request to the Docker API to fetch a list
     * of containers in JSON format. The JSON response is then converted into
     * a `Containers` object.
     *
     * @param ContainersQuery $query The query parameters for filtering containers
     *
     * @return Containers A collection of Docker containers.
     *
     * @throws Exception If an error occurs during the HTTP request.
     * @throws InvalidArgumentException If the HTTP client is not initialized.
     * @throws JsonException If the JSON response cannot be parsed.
     */
    #[Override]
    public function getContainers(ContainersQuery|null $query = null): Containers
    {
        try {
            if (self::$client === null) {
                throw new InvalidArgumentException('HTTP client is not initialized');
            }

            $response = self::$client->get(
                '/containers/json' . ($query === null ? '' : '?' . $query->toQueryString()),
                self::$requestOptions,
            );
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $containersJson = $response->getBody()->getContents();

        return Containers::fromJson($containersJson);
    }
}
