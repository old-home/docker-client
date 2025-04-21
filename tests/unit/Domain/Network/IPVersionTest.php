<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\IPVersion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the IPVersion enum, which represents
 * the IP version (IPv4 or IPv6) and provides related utility methods such
 * as bit length and byte length.
 */
#[CoversClass(IPVersion::class)]
final class IPVersionTest extends TestCase
{
    /**
     * Tests that the values of the IPVersion enum are correctly defined.
     */
    public function testIPVersionValues(): void
    {
        $this->assertSame('IPv4', IPVersion::IPV4->value);
        $this->assertSame('IPv6', IPVersion::IPV6->value);
    }

    /**
     * Tests that the bit length for each IP version is correctly returned.
     */
    public function testBitLength(): void
    {
        $this->assertSame(32, IPVersion::IPV4->bitLength());
        $this->assertSame(128, IPVersion::IPV6->bitLength());
    }

    /**
     * Tests that the byte length for each IP version is correctly returned.
     */
    public function testByteLength(): void
    {
        $this->assertSame(4, IPVersion::IPV4->byteLength());
        $this->assertSame(16, IPVersion::IPV6->byteLength());
    }
}
