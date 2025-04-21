<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Infrastructures;

use Graywings\DockerClient\Domain\Network\Uri;
use GuzzleHttp\Client;

use const CURLOPT_UNIX_SOCKET_PATH;

/**
 * Connect to Docker Engine API Gateway
 */
abstract class DockerEngineApiGateway extends HttpGateway
{
    /**
     * @var array<string, string> $requestOptions
     */
    protected static array $requestOptions = ['Host' => 'docker'];

    /**
     * This constructor initializes the shared HTTP client if it has not already
     * been created. The client is configured to use the Docker Unix socket for
     * communication with the Docker API.
     *
     * @param string $uri The uri to the Docker Engine API.
     *                           Defaults to `unix:///var/run/docker.sock`.
     */
    public function __construct(
        string $uri = 'unix:///var/run/docker.sock',
    ) {
        if (self::$client !== null) {
            return;
        }

        $uri = Uri::parse($uri);

        if ($uri->scheme === 'unix') {
            self::$client = new Client(
                [
                    'base_uri' => 'http://localhost',
                    'curl' => [CURLOPT_UNIX_SOCKET_PATH => $uri->path],
                ],
            );

            return;
        }

        self::$client = new Client(
            [
                'base_uri' => (string) $uri,
            ],
        );
    }
}
