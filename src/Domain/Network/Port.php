<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

readonly final class Port
{
    public function __construct(
        private(set) IPAddress $ipAddress,
        private(set) PortNumber $privatePort,
        private(set) PortNumber $publicPort,
        private(set) TransportProtocol $transportProtocol,
    ) {
    }
}
