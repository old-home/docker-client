<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use Ramsey\Collection\Collection;

use function array_map;

/**
 * The Ports class manages a collection of port mapping configurations for Docker containers.
 *
 * This class holds port configuration information retrieved from the Docker API in a structured form,
 * allowing consistent access throughout the application. Each port information is represented
 * as a {@see Port} object, containing details such as IP address, private port,
 * public port, and protocol type.
 *
 * Main features:
 * - Converts response data from Docker API into structured objects
 * - Provides centralized management of port configurations
 * - Offers an immutable data structure
 *
 * Usage example:
 * ```
 * $portsArray = [...]; // Port information retrieved from Docker API
 * $ports = Ports::fromArray($portsArray);
 * ```
 *
 * @see Port Class representing individual port information
 *
 * @immutable This class is immutable (read-only)
 */
final readonly class Ports
{
    /**
     * @param Collection<Port> $values
     */
    public function __construct(
        private(set) Collection $values,
    ) {
    }

    /**
     * @param array{
     *    IP?: string,
     *    PrivatePort: int,
     *    PublicPort?: int,
     *    Type: string
     *   }[] $portsArray
     */
    public static function fromArray(array $portsArray): self
    {
        return new self(new Collection(
            Port::class,
            array_map(
                static fn (array $value) => Port::fromArray($value),
                $portsArray,
            ),
        ));
    }
}
