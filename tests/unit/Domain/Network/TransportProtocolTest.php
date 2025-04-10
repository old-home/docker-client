<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\TransportProtocol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransportProtocol::class)]
final class TransportProtocolTest extends TestCase
{
    public function testTransportProtocolValues(): void
    {
        $this->assertSame('tcp', TransportProtocol::TCP->value);
        $this->assertSame('udp', TransportProtocol::UDP->value);
        $this->assertSame('sctp', TransportProtocol::SCTP->value);
        $this->assertSame('dccp', TransportProtocol::DCCP->value);
        $this->assertSame('quic', TransportProtocol::QUIC->value);
    }
}
