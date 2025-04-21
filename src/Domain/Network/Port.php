<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use function array_key_exists;

/**
 * This class represents a network port associated with a Docker container.
 * It encapsulates details such as the IP address, private port, public port,
 * and transport protocol (e.g., TCP or UDP).
 *
 * The class provides a structured way to manage port-related data, ensuring
 * consistency and reducing the risk of errors when working with network configurations.
 */
readonly final class Port
{
    /**
     * @param IPAddress|null    $ipAddress         The IP address associated with the port.
     * @param PortNumber        $privatePort       The private port inside the container.
     * @param PortNumber|null   $publicPort        The public port exposed on the host.
     * @param TransportProtocol $transportProtocol The transport protocol (e.g., TCP or UDP).
     */
    public function __construct(
        private(set) IPAddress|null $ipAddress,
        private(set) PortNumber $privatePort,
        private(set) PortNumber|null $publicPort,
        private(set) TransportProtocol $transportProtocol,
    ) {
    }

    /**
     * @param array{
     *  IP?: string,
     *  PrivatePort: int,
     *  PublicPort?: int,
     *  Type: string
     * } $portsArray
     */
    public static function fromArray(array $portsArray): self
    {
        return new self(
            array_key_exists('IP', $portsArray)
                ? IPAddress::parse($portsArray['IP'])
                : null,
            new PortNumber($portsArray['PrivatePort']),
            array_key_exists('PublicPort', $portsArray)
                ? new PortNumber($portsArray['PublicPort'])
                : null,
            TransportProtocol::from($portsArray['Type']),
        );
    }
}
