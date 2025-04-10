<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use Graywings\DockerClient\Domain\Network\Port;
use stdClass;

/**
 * Container class
 *
 * Represent docker container.
 */
readonly final class Container
{
    /**
     * @param string         $id         The ID of this container
     * @param string[]       $names      The names that this container has been given
     * @param string         $image      The name of the image used when creating this container
     * @param string         $imageId    The ID of the image that this container was created from
     * @param string         $command    Command to run when starting the container
     * @param int            $created    When the container was created
     * @param Port[]         $ports      The ports exposed by this container
     * @param Labels         $labels     The labels of container
     * @param ContainerState $state      State of container
     * @param string         $status     The status of this container
     * @param int|null       $sizeRw     The size of the container's read-write layer
     * @param int|null       $sizeRootFs The total size of all the layers in the container
     */
    public function __construct(
        private(set) string $id,
        private(set) array $names,
        private(set) string $image,
        private(set) string $imageId,
        private(set) string $command,
        private(set) int $created,
        private(set) array $ports,
        private(set) Labels $labels,
        private(set) ContainerState $state,
        private(set) string $status,
        private(set) int|null $sizeRw = null,
        private(set) int|null $sizeRootFs = null,
    ) {
    }

    public static function fromStdClass(stdClass $containerAssociate): self
    {
        return new self(
            $containerAssociate->Id,
            $containerAssociate->Names,
            $containerAssociate->Image,
            $containerAssociate->ImageID,
            $containerAssociate->Command,
            $containerAssociate->Created,
            $containerAssociate->Ports,
            Labels::fromStdClass($containerAssociate->Labels),
            ContainerState::from($containerAssociate->State),
            $containerAssociate->Status,
            $containerAssociate->SizeRw ?? null,
            $containerAssociate->SizeRootFs ?? null,
        );
    }
}
