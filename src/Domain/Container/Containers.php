<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use Ramsey\Collection\Collection;

use function array_map;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * This class represents a collection of Docker containers. It provides a structured
 * way to manage and interact with multiple `Container` objects, such as creating
 * a collection from JSON data or accessing the underlying container objects.
 */
readonly final class Containers
{
    /**
     * @param Collection<Container> $values A collection of `Container` objects.
     */
    public function __construct(
        private(set) Collection $values,
    ) {
    }

    /**
     * Builds a `Containers` instance from a JSON string.
     *
     * This method parses a JSON string containing an array of container data,
     * converts each container into a `Container` object, and stores them in a
     * `Collection` object.
     *
     * @param string $containersJson A JSON string representing an array of containers.
     *
     * @return self A new `Containers` instance containing the parsed `Container` objects.
     */
    public static function fromJson(string $containersJson): self
    {
        /**
         * @var array<array{
         *  Id: string,
         *  Names: string[],
         *  Image: string,
         *  ImageID: string,
         *  ImageManifestDescriptor: array{
         *   mediaType: string,
         *   digest: string,
         *   size: string,
         *   urls: string[]|null,
         *   annotations: array<string, string>,
         *   data: string|null,
         *   platform: array{
         *    architecture: string,
         *    os: string,
         *    "os.version": string,
         *    "os.features": string[],
         *    variant: string
         *   },
         *   artifactType: string|null
         *  },
         *  Command: string,
         *  Created: int,
         *  Ports: array{
         *   IP?: string,
         *   PrivatePort: int,
         *   PublicPort?: int,
         *   Type: string
         *  }[],
         *  SizeRw?: int,
         *  SizeRootFs?: int,
         *  Labels: array<string, string>,
         *  State: string,
         *  Status: string,
         *  HostConfig: array{
         *   NetworkMode: string,
         *   Annotations: array<string, string>|null
         *  },
         *  NetworkSettings: array{
         *   Networks: array<string, array{
         *    IPAMConfig: array{
         *     IPv4Address: string,
         *     IPv6Address: string,
         *     LinkLocalIPs: string[]
         *    }|null,
         *    Links: string[]|null,
         *    MacAddress: string,
         *    Aliases: string[]|null,
         *    DriverOpts: array<string, string>|null,
         *    GwPriority?: int,
         *    NetworkID: string,
         *    EndpointID: string,
         *    Gateway: string,
         *    IPAddress: string,
         *    IPPrefixLen: int,
         *    IPv6Gateway: string,
         *    GlobalIPv6Address: string,
         *    GlobalIPv6PrefixLen: int,
         *    DNSNames: string[]|null
         *   }>
         *  },
         *  Mounts: array{
         *   Type: string,
         *   Name: string,
         *   Source: string,
         *   Destination: string,
         *   Driver: string,
         *   Mode: string,
         *   RW: bool,
         *   Propagation: string
         *  }[]
         * }> $containersArray Docker Engine API(/list/containers/json) response
         */
        $containersArray = json_decode($containersJson, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            new Collection(
                Container::class,
                array_map(
                    static function (array $containerArray) {
                        return Container::fromArray($containerArray);
                    },
                    $containersArray,
                ),
            ),
        );
    }
}
