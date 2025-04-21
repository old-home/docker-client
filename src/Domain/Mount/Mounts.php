<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Mount;

use Ramsey\Collection\Collection;

use function array_map;

/**
 * This class represents a collection of Docker mount configurations. It provides
 * a structured way to manage and interact with multiple `Mount` objects, such as
 * initializing a collection of mounts and accessing the underlying data.
 */
readonly final class Mounts
{
    /**
     * @param Collection<Mount> $mounts A collection of `Mount` objects representing
     *                                  the mounts associated with a Docker container.
     */
    public function __construct(
        private(set) Collection $mounts,
    ) {
    }

    /**
     * @param array<array{
     *  Type: string,
     *  Name: string,
     *  Source: string,
     *  Destination: string,
     *  Driver: string,
     *  Mode: string,
     *  RW: bool,
     *  Propagation: string
     * }> $mountsArray
     */
    public static function fromArray(array $mountsArray): self
    {
        return new self(
            new Collection(
                Mount::class,
                array_map(
                    static fn (array $mountArray) => Mount::fromArray($mountArray),
                    $mountsArray,
                ),
            ),
        );
    }
}
