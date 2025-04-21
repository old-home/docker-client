<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Mount;

/**
 * Enum StorageDriver
 *
 * This enum represents the various Docker storage drivers. It provides a
 * strongly-typed way to handle storage driver types, ensuring consistency
 * and reducing the risk of errors when working with mount configurations.
 *
 * The storage drivers include:
 * - `Overlay2`: A modern storage driver for Linux, optimized for performance.
 * - `FuseOverlayfs`: A FUSE-based implementation of the overlay filesystem.
 * - `Btrfs`: A storage driver based on the Btrfs filesystem.
 * - `Zfs`: A storage driver based on the ZFS filesystem.
 * - `Vfs`: A simple storage driver that does not use copy-on-write.
 */
enum StorageDriver: string
{
    case Overlay2      = 'overlay2';
    case FuseOverlayfs = 'fuse-overlayfs';
    case Btrfs         = 'btrfs';
    case Zfs           = 'zfs';
    case Vfs           = 'vfs';
}
