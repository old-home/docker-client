<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\TransportProtocol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the TransportProtocol enum, which represents
 * various transport layer protocols used in networking. It validates that the
 * enum values are correctly defined and match the expected protocol names.
 */
#[CoversClass(TransportProtocol::class)]
final class TransportProtocolTest extends TestCase
{
    /**
     * Tests the values of the TransportProtocol enum.
     *
     * This test ensures that the enum values for each transport protocol
     * (e.g., TCP, UDP, SCTP, DCCP, QUIC) are correctly defined and match
     * the expected string representations.
     */
    public function testTransportProtocolValues(): void
    {
        $this->assertSame('tcp', TransportProtocol::TCP->value);
        $this->assertSame('udp', TransportProtocol::UDP->value);
        $this->assertSame('sctp', TransportProtocol::SCTP->value);
        $this->assertSame('dccp', TransportProtocol::DCCP->value);
        $this->assertSame('quic', TransportProtocol::QUIC->value);
    }
}
