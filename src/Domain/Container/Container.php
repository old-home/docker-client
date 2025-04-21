<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use Graywings\DockerClient\Domain\Mount\Mounts;
use Graywings\DockerClient\Domain\Network\NetworkSettings;
use Graywings\DockerClient\Domain\Network\Ports;

/**
 * This class represents a Docker container and encapsulates its properties,
 * such as ID, names, image, command, state, and more. It provides a structured
 * way to manage and interact with container data retrieved from the Docker API.
 *
 * The class is immutable (`readonly`) and ensures that its properties can only
 * be set during instantiation or through controlled methods.
 */
readonly final class Container
{
    /**
     * @param string          $id              The unique identifier of the container.
     * @param string[]        $names           An array of names assigned to the container.
     * @param string          $image           The name of the image used to create the container.
     * @param string          $imageId         The unique identifier of the image used.
     * @param string          $command         The command executed when the container starts.
     * @param int             $created         The timestamp (UNIX epoch) when the container was created.
     * @param Ports           $ports           An array of ports exposed by the container.
     * @param Labels          $labels          Key-value pairs (labels) associated with the container.
     * @param ContainerState  $state           The current state of the container (e.g., running, exited).
     * @param string          $status          A human-readable status of the container.
     * @param Mounts          $mounts          The mounts associated with the container (e.g., volumes, bind mounts).
     * @param NetworkSettings $networkSettings The network settings of the container, including connected networks.
     * @param int|null        $sizeRw          The size of the container's read-write layer, in bytes.
     * @param int|null        $sizeRootFs      The total size of all layers in the container, in bytes.
     */
    public function __construct(
        private(set) string $id,
        private(set) array $names,
        private(set) string $image,
        private(set) string $imageId,
        private(set) string $command,
        private(set) int $created,
        private(set) Ports $ports,
        private(set) Labels $labels,
        private(set) ContainerState $state,
        private(set) string $status,
        private(set) Mounts $mounts,
        private(set) NetworkSettings $networkSettings,
        private(set) int|null $sizeRw = null,
        private(set) int|null $sizeRootFs = null,
    ) {
    }

    /**
     * Creates a `Container` object from a `stdClass` object.
     *
     * This method is used to convert raw container data (typically retrieved
     * from the Docker API in JSON format and decoded into a `stdClass` object)
     * into a structured `Container` object.
     *
     * @param array{
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
     *  Mounts: array<array{
     *   Type: string,
     *   Name: string,
     *   Source: string,
     *   Destination: string,
     *   Driver: string,
     *   Mode: string,
     *   RW: bool,
     *   Propagation: string
     *  }>
     * } $containerArray The raw container data as a `stdClass` object.
     *
     * @return self A new `Container` object populated with the provided data.
     */
    public static function fromArray(array $containerArray): self
    {
        return new self(
            $containerArray['Id'],
            $containerArray['Names'],
            $containerArray['Image'],
            $containerArray['ImageID'],
            $containerArray['Command'],
            $containerArray['Created'],
            Ports::fromArray($containerArray['Ports']),
            Labels::fromArray($containerArray['Labels']),
            ContainerState::from($containerArray['State']),
            $containerArray['Status'],
            Mounts::fromArray($containerArray['Mounts']),
            NetworkSettings::fromArray($containerArray['NetworkSettings']),
            $containerArray['SizeRw'] ?? null,
            $containerArray['SizeRootFs'] ?? null,
        );
    }
}
