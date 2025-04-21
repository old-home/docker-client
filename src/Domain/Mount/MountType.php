<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Mount;

/**
 * Enum MountType
 *
 * This enum represents the various types of Docker mounts. It provides a
 * strongly-typed way to handle mount types, ensuring consistency and reducing
 * the risk of errors when working with mount configurations.
 *
 * The mount types include:
 * - `Bind`: A bind mount, which maps a host directory or file to a container.
 * - `Volume`: A Docker-managed volume.
 * - `Tmpfs`: A temporary filesystem mount.
 * - `Image`: A mount based on a Docker image.
 * - `Npipe`: A named pipe mount (Windows-specific).
 * - `Cluster`: A cluster volume mount.
 */
enum MountType: string
{
    case Bind    = 'bind';
    case Volume  = 'volume';
    case Tmpfs   = 'tmpfs';
    case Image   = 'image';
    case Npipe   = 'npipe';
    case Cluster = 'cluster';
}
