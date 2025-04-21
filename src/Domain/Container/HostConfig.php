<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

/**
 * This class represents the host configuration for a Docker container. It includes
 * settings such as the network mode and additional annotations that define how the
 * container interacts with the host system.
 *
 * The `HostConfig` class provides a structured way to manage and parse host-related
 * configurations, ensuring consistency and type safety.
 */
readonly final class HostConfig
{
    /**
     * @param NetworkMode          $networkMode The network mode for the container.
     * @param array<string,string> $annotations An array of annotations for the container.
     */
    public function __construct(
        private(set) NetworkMode $networkMode,
        private(set) array $annotations,
    ) {
    }

    /**
     * Creates a `HostConfig` object from a `stdClass` object.
     *
     * This method parses a `stdClass` object (typically retrieved from the Docker API)
     * and extracts the `NetworkMode` and `Annotations` properties to initialize a
     * `HostConfig` instance.
     *
     * @param array{
     *  NetworkMode: string,
     *  Annotations: array<string, string>|null
     * } $hostConfigArray The `stdClass` object containing host configuration data.
     *
     * @return self A new `HostConfig` object.
     */
    public static function fromArray(array $hostConfigArray): self
    {
        $annotationsArray = $hostConfigArray['Annotations'] ?? [];

        return new self(
            NetworkMode::parse($hostConfigArray['NetworkMode']),
            $annotationsArray,
        );
    }
}
