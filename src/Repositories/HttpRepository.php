<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Repositories;

use GuzzleHttp\Client;

use const CURLOPT_UNIX_SOCKET_PATH;

abstract class HttpRepository
{
    protected static Client|null $client = null;

    /**
     * @var array<string, string> $requestOptions
     */
    public static array $requestOptions = [
        'Host' => 'docker',
        'Content-Type' => 'application/json',
    ];

    public function __construct(
        string $socketPath = '/var/run/docker.sock',
    ) {
        if (self::$client !== null) {
            return;
        }

        self::$client = new Client(
            [
                'base_uri' => 'http://localhost',
                'curl' => [CURLOPT_UNIX_SOCKET_PATH => $socketPath],
            ],
        );
    }
}
