<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use function array_map;

/**
 * This class represents the IP Address Management (IPAM) configuration for a network.
 * It encapsulates the IPv4 and IPv6 addresses assigned to a network, as well as any
 * link-local IP addresses associated with it.
 *
 * The class provides a structured way to manage IPAM-related data, ensuring consistency
 * and reducing the risk of errors when working with network configurations.
 */
readonly final class IpamConfig
{
    /**
     * @param IPAddress   $ipV4Address  The IPv4 address assigned to the network.
     * @param IPAddress   $ipV6Address  The IPv6 address assigned to the network.
     * @param IPAddress[] $linkLocalIPs An array of link-local IP addresses associated with the network.
     */
    public function __construct(
        private(set) IPAddress $ipV4Address,
        private(set) IPAddress $ipV6Address,
        private(set) array $linkLocalIPs,
    ) {
    }

    /**
     * Creates an `IpamConfig` object from a `stdClass` object.
     *
     * This method parses a `stdClass` object (typically retrieved from the Docker API)
     * and extracts the IPAM configuration details to initialize an `IpamConfig` instance.
     *
     * @param array{
     *  IPv4Address: string,
     *  IPv6Address: string,
     *  LinkLocalIPs: string[]
     * } $ipamConfigArray The `stdClass` object containing IPAM configuration data.
     *
     * @return self A new `IpamConfig` object populated with the parsed data.
     */
    public static function fromArray(array $ipamConfigArray): self
    {
        return new self(
            IPAddress::parse($ipamConfigArray['IPv4Address']),
            IPAddress::parse($ipamConfigArray['IPv6Address']),
            array_map(
                static fn (string $linkLocalIP) => IPAddress::parse($linkLocalIP),
                $ipamConfigArray['LinkLocalIPs'],
            ),
        );
    }
}
