<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\IPVersion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(IPVersion::class)]
final class IPVersionTest extends TestCase
{
    public function testIPVersionValues(): void
    {
        $this->assertSame('IPv4', IPVersion::IPV4->value);
        $this->assertSame('IPv6', IPVersion::IPV6->value);
    }

    public function testBitLength(): void
    {
        $this->assertSame(32, IPVersion::IPV4->bitLength());
        $this->assertSame(128, IPVersion::IPV6->bitLength());
    }

    public function testByteLength(): void
    {
        $this->assertSame(4, IPVersion::IPV4->byteLength());
        $this->assertSame(16, IPVersion::IPV6->byteLength());
    }
}
