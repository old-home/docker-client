<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

/**
 * Enum NetworkDriver
 *
 * This enum represents the available network drivers in Docker. Each driver
 * defines a specific type of network configuration and behavior for containers.
 *
 * - `Bridge`: The default network driver, used for standalone containers.
 * - `Host`: Shares the host's network stack with the container.
 * - `MacVLan`: Assigns a MAC address to the container, allowing it to appear as a physical device on the network.
 * - `Overlay`: Enables multi-host networking for Docker Swarm or other orchestrators.
 * - `IPVLan`: Similar to MacVLan but operates at the IP layer.
 * - `None`: Disables networking for the container.
 */
enum NetworkDriver: string
{
    case Bridge  = 'bridge';
    case Host    = 'host';
    case MacVLan = 'macvlan';
    case Overlay = 'overlay';
    case IPVLan  = 'ipvlan';
    case None    = 'none';
}
