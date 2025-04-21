<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\IPVersion;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the IPAddress class, which represents
 * an IP address and provides methods for parsing, validating, and comparing
 * IP addresses.
 */
#[CoversClass(IPAddress::class)]
#[CoversClass(IPVersion::class)]
final class IPAddressTest extends TestCase
{
    /**
     * Tests parsing a valid IPv4 address.
     */
    public function testParseIPv4(): void
    {
        $ipAddress = IPAddress::parse('192.168.1.1');
        $this->assertSame('192.168.1.1', (string) $ipAddress);
        $this->assertSame(IPVersion::IPV4, $ipAddress->ipVersion);
    }

    /**
     * Tests parsing a valid IPv6 address.
     */
    public function testParseIPv6(): void
    {
        $ipAddress = IPAddress::parse('2001:db8:85a3::8a2e:370:7334');
        $this->assertSame('2001:db8:85a3::8a2e:370:7334', (string) $ipAddress);
        $this->assertSame(IPVersion::IPV6, $ipAddress->ipVersion);
    }

    /**
     * Tests parsing an invalid IP address.
     */
    public function testParseInvalidIP(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP address format: invalid-ip');
        IPAddress::parse('invalid-ip');
    }

    /**
     * Tests parsing an IPv4 address with an invalid format.
     */
    public function testParseInvalidIPv4Format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP address format: 256.256.256.256');
        IPAddress::parse('256.256.256.256');
    }

    /**
     * Tests parsing an IPv6 address with an invalid format.
     */
    public function testParseInvalidIPv6Format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP address format: 2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3');
        IPAddress::parse('2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3');
    }

    /**
     * Tests parsing an empty IP address string.
     */
    public function testParseEmptyIP(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP address format: ');
        IPAddress::parse('');
    }

    /**
     * Tests comparing two IP addresses for equality.
     */
    public function testEquals(): void
    {
        $ip1 = IPAddress::parse('192.168.1.1');
        $ip2 = IPAddress::parse('192.168.1.1');
        $ip3 = IPAddress::parse('192.168.1.2');

        $this->assertTrue($ip1->equals($ip2));
        $this->assertFalse($ip1->equals($ip3));
    }
}
