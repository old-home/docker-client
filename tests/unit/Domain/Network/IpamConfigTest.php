<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\IpamConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the IPAMConfig class, which represents
 * the IP Address Management (IPAM) configuration for a network. It tests
 * the creation and validation of IPAM configurations, including IPv4 and
 * IPv6 addresses and link-local IPs.
 */
#[CoversClass(IpamConfig::class)]
#[CoversClass(IPAddress::class)]
final class IpamConfigTest extends TestCase
{
    /**
     * Tests the creation of an IPAMConfig object with valid IPv4 and IPv6 addresses
     * and a list of link-local IPs.
     */
    public function testCanCreateIPAMConfig(): void
    {
        $ipv4Address  = IPAddress::parse('192.168.1.10');
        $ipv6Address  = IPAddress::parse('2001:db8::1');
        $linkLocalIPs = [
            IPAddress::parse('169.254.1.1'),
            IPAddress::parse('169.254.1.2'),
        ];

        $ipamConfig = new IpamConfig(
            ipV4Address: $ipv4Address,
            ipV6Address: $ipv6Address,
            linkLocalIPs: $linkLocalIPs,
        );

        $this->assertSame($ipv4Address, $ipamConfig->ipV4Address);
        $this->assertSame($ipv6Address, $ipamConfig->ipV6Address);
        $this->assertSame($linkLocalIPs, $ipamConfig->linkLocalIPs);
    }

    /**
     * Tests the creation of an IPAMConfig object with valid IPv4 and IPv6 addresses
     * but an empty list of link-local IPs.
     */
    public function testCanCreateIPAMConfigWithEmptyLinkLocalIPs(): void
    {
        $ipv4Address  = IPAddress::parse('192.168.1.10');
        $ipv6Address  = IPAddress::parse('2001:db8::1');
        $linkLocalIPs = [];

        $ipamConfig = new IpamConfig(
            ipV4Address: $ipv4Address,
            ipV6Address: $ipv6Address,
            linkLocalIPs: $linkLocalIPs,
        );

        $this->assertSame($ipv4Address, $ipamConfig->ipV4Address);
        $this->assertSame($ipv6Address, $ipamConfig->ipV6Address);
        $this->assertSame($linkLocalIPs, $ipamConfig->linkLocalIPs);
    }

    /**
     * Tests the creation of an IPAMConfig object with a populated list of link-local IPs.
     *
     * This test ensures that the `linkLocalIPs` property is correctly set when
     * a list of link-local IPs is provided.
     */
    public function testCanCreateIPAMConfigWithLinkLocalIPs(): void
    {
        $ipv4Address  = IPAddress::parse('192.168.1.10');
        $ipv6Address  = IPAddress::parse('2001:db8::1');
        $linkLocalIPs = [
            IPAddress::parse('169.254.1.1'),
            IPAddress::parse('169.254.1.2'),
            IPAddress::parse('169.254.1.3'),
        ];

        $ipamConfig = new IpamConfig(
            ipV4Address: $ipv4Address,
            ipV6Address: $ipv6Address,
            linkLocalIPs: $linkLocalIPs,
        );

        $this->assertSame($ipv4Address, $ipamConfig->ipV4Address);
        $this->assertSame($ipv6Address, $ipamConfig->ipV6Address);
        $this->assertCount(3, $ipamConfig->linkLocalIPs);
        $this->assertSame('169.254.1.1', (string) $ipamConfig->linkLocalIPs[0]);
        $this->assertSame('169.254.1.2', (string) $ipamConfig->linkLocalIPs[1]);
        $this->assertSame('169.254.1.3', (string) $ipamConfig->linkLocalIPs[2]);
    }

    /**
     * Tests the creation of an IPAMConfig object from a valid stdClass object.
     *
     * This test ensures that the `fromStdClass` method correctly parses a
     * stdClass object and initializes an `IpamConfig` instance with the
     * appropriate IPv4, IPv6, and link-local IP addresses.
     */
    public function testFromArray(): void
    {
        $stdClass                 = [];
        $stdClass['IPv4Address']  = '192.168.1.10';
        $stdClass['IPv6Address']  = '2001:db8::1';
        $stdClass['LinkLocalIPs'] = [
            '169.254.1.1',
            '169.254.1.2',
        ];

        $ipamConfig = IpamConfig::fromArray($stdClass);

        $this->assertSame('192.168.1.10', (string) $ipamConfig->ipV4Address);
        $this->assertSame('2001:db8::1', (string) $ipamConfig->ipV6Address);
        $this->assertCount(2, $ipamConfig->linkLocalIPs);
        $this->assertSame('169.254.1.1', (string) $ipamConfig->linkLocalIPs[0]);
        $this->assertSame('169.254.1.2', (string) $ipamConfig->linkLocalIPs[1]);
    }

    /**
     * Tests the creation of an IPAMConfig object from a stdClass object with an empty list of link-local IPs.
     *
     * This test ensures that the `fromStdClass` method correctly handles a
     * stdClass object where the `linkLocalIPs` property is an empty array.
     */
    public function testFromArrayWithEmptyLinkLocalIPs(): void
    {
        $stdClass                 = [];
        $stdClass['IPv4Address']  = '192.168.1.10';
        $stdClass['IPv6Address']  = '2001:db8::1';
        $stdClass['LinkLocalIPs'] = [];

        $ipamConfig = IpamConfig::fromArray($stdClass);

        $this->assertSame('192.168.1.10', (string) $ipamConfig->ipV4Address);
        $this->assertSame('2001:db8::1', (string) $ipamConfig->ipV6Address);
        $this->assertCount(0, $ipamConfig->linkLocalIPs);
    }
}
