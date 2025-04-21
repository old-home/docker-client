<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Infrastructures;

use GuzzleHttp\Client;

/**
 * This abstract class provides a base implementation for HTTP-based repositories
 * that interact with the Docker API. It initializes a shared HTTP client using
 * Guzzle and sets default request options, such as the Docker socket path and
 * base URI.
 *
 * Classes extending this repository can use the shared HTTP client to perform
 * API requests.
 */
abstract class HttpGateway
{
    /**
     * @var Client|null A shared HTTP client instance for making API requests.
     */
    protected static Client|null $client = null;
}
