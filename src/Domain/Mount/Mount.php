<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Mount;

/**
 * This class represents a Docker mount configuration. It encapsulates the
 * properties of a mount, such as its type, name, source, destination, driver,
 * mode, read/write status, and propagation settings.
 *
 * Mounts are used to define how storage is attached to a container, providing
 * flexibility in managing containerized applications.
 */
readonly final class Mount
{
    /**
     * @param MountType     $type        The type of the mount (e.g., bind, volume, tmpfs).
     * @param string        $name        The name of the mount.
     * @param string        $source      The source path of the mount on the host system.
     * @param string        $destination The destination path of the mount inside the container.
     * @param StorageDriver $driver      The storage driver used for the mount.
     * @param string        $mode        The access mode of the mount (e.g., read-only or read-write).
     * @param bool          $rw          Whether the mount is read/write (`true`) or read-only (`false`).
     * @param string        $propagation The propagation setting for the mount (e.g., rprivate, rshared).
     */
    public function __construct(
        private(set) MountType $type,
        private(set) string $name,
        private(set) string $source,
        private(set) string $destination,
        private(set) StorageDriver $driver,
        private(set) string $mode,
        private(set) bool $rw,
        private(set) string $propagation,
    ) {
    }

    /**
     * @param array{
     *  Type: string,
     *  Name: string,
     *  Source: string,
     *  Destination: string,
     *  Driver: string,
     *  Mode: string,
     *  RW: bool,
     *  Propagation: string
     * } $mountStdClass
     */
    public static function fromArray(array $mountStdClass): self
    {
        return new self(
            MountType::from($mountStdClass['Type']),
            $mountStdClass['Name'],
            $mountStdClass['Source'],
            $mountStdClass['Destination'],
            StorageDriver::from($mountStdClass['Driver']),
            $mountStdClass['Mode'],
            $mountStdClass['RW'],
            $mountStdClass['Propagation'],
        );
    }
}
