<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Infrastructures\Repositories\Container;

use Graywings\DockerClient\Domain\Container\Containers;
use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Exceptions\Exception;
use Graywings\DockerClient\Infrastructures\Repositories\HttpRepository;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

final class ContainerRepository extends HttpRepository implements IContainerRepository
{
    /**
     * @throws Exception|InvalidArgumentException
     */
    public function getContainers(): Containers
    {
        try {
            if (self::$client === null) {
                throw new InvalidArgumentException();
            }

            $response = self::$client->get('/containers/json', self::$requestOptions);
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $containersJson = $response->getBody()->getContents();

        return Containers::fromJson($containersJson);
    }
}
