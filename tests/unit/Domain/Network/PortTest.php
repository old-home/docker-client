<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\Port;
use Graywings\DockerClient\Domain\Network\PortNumber;
use Graywings\DockerClient\Domain\Network\TransportProtocol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Port::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(PortNumber::class)]
#[CoversClass(TransportProtocol::class)]
final class PortTest extends TestCase
{
    public function testCreatePort(): void
    {
        $ipAddress         = IPAddress::parse('127.0.0.1');
        $privatePort       = new PortNumber(8080);
        $publicPort        = new PortNumber(80);
        $transportProtocol = TransportProtocol::TCP;

        $port = new Port($ipAddress, $privatePort, $publicPort, $transportProtocol);

        $this->assertSame($ipAddress, $port->ipAddress);
        $this->assertSame($privatePort, $port->privatePort);
        $this->assertSame($publicPort, $port->publicPort);
        $this->assertSame($transportProtocol, $port->transportProtocol);
    }
}
