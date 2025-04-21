<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\CIDRBlock;
use Graywings\DockerClient\Domain\Network\DriverOption;
use Graywings\DockerClient\Domain\Network\DriverOptions;
use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\IpamConfig;
use Graywings\DockerClient\Domain\Network\MacAddress;
use Graywings\DockerClient\Domain\Network\NetworkSetting;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Provides unit tests for the NetworkSetting class.
 * It tests various scenarios of the creation and validation of a NetworkSetting
 * instance from an array input.
 */
#[CoversClass(CIDRBlock::class)]
#[CoversClass(DriverOption::class)]
#[CoversClass(DriverOptions::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(MacAddress::class)]
#[CoversClass(IpamConfig::class)]
#[CoversClass(NetworkSetting::class)]
final class NetworkSettingTest extends TestCase
{
    /**
     * Tests the function `fromArray` of the `NetworkSetting` class with a complete dataset.
     *
     * This method validates the transformation of an array of network settings into a `NetworkSetting` instance
     * and asserts that all expected properties and configurations have been correctly set.
     */
    public function testFromArrayWithCompleteData(): void
    {
        $data = [
            'IPAMConfig' => [
                'IPv4Address' => '192.168.0.1',
                'IPv6Address' => 'fd00::1',
                'LinkLocalIPs' => ['127.0.0.1', '::1'],
            ],
            'Links' => ['container1', 'container2'],
            'MacAddress' => '02:42:ac:11:00:03',
            'Aliases' => ['alias1', 'alias2'],
            'DriverOpts' => ['bridge' => 'value1', 'overlay' => 'value2'],
            'GwPriority' => 1,
            'NetworkID' => 'network-id',
            'EndpointID' => 'endpoint-id',
            'Gateway' => '192.168.1.1',
            'IPAddress' => '192.168.1.100',
            'IPPrefixLen' => 24,
            'IPv6Gateway' => 'fd00::1',
            'GlobalIPv6Address' => '2001:db8::1',
            'GlobalIPv6PrefixLen' => 64,
            'DNSNames' => ['dns1', 'dns2'],
        ];

        $networkSetting = NetworkSetting::fromArray('test_network', $data);

        $this->assertSame('test_network', $networkSetting->name);
        $this->assertInstanceOf(IpamConfig::class, $networkSetting->ipamConfig);
        $this->assertSame(['container1', 'container2'], $networkSetting->links);
        $this->assertInstanceOf(MacAddress::class, $networkSetting->macAddress);
        $this->assertSame(['alias1', 'alias2'], $networkSetting->aliases);
        $this->assertInstanceOf(DriverOptions::class, $networkSetting->driverOpts);
        $this->assertSame(1, $networkSetting->gwPriority);
        $this->assertSame('network-id', $networkSetting->id);
        $this->assertSame('endpoint-id', $networkSetting->endpointId);
        $this->assertInstanceOf(IPAddress::class, $networkSetting->gateway);
        $this->assertInstanceOf(CIDRBlock::class, $networkSetting->ipAddress);
        $this->assertInstanceOf(IPAddress::class, $networkSetting->ipv6Gateway);
        $this->assertInstanceOf(CIDRBlock::class, $networkSetting->ipv6Address);
        $this->assertSame(['dns1', 'dns2'], $networkSetting->dnsNames);
    }

    /**
     * Tests the creation of a NetworkSetting object using an array of partial data.
     *
     * This test verifies that the `fromArray` method of the `NetworkSetting` class
     * correctly handles partial input data and appropriately initializes the object
     * properties. It ensures that null, default, and required values are set correctly,
     * and that the appropriate types are assigned for specific properties.
     */
    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'IPAMConfig' => null,
            'Links' => null,
            'MacAddress' => '02:42:ac:11:00:03',
            'Aliases' => null,
            'DriverOpts' => null,
            'GwPriority' => null,
            'NetworkID' => 'network-id',
            'EndpointID' => 'endpoint-id',
            'Gateway' => '192.168.1.1',
            'IPAddress' => '192.168.1.100',
            'IPPrefixLen' => 24,
            'IPv6Gateway' => '',
            'GlobalIPv6Address' => '',
            'GlobalIPv6PrefixLen' => 0,
            'DNSNames' => null,
        ];

        $networkSetting = NetworkSetting::fromArray('test_network', $data);

        $this->assertNull($networkSetting->ipamConfig);
        $this->assertSame([], $networkSetting->links);
        $this->assertInstanceOf(MacAddress::class, $networkSetting->macAddress);
        $this->assertSame([], $networkSetting->aliases);
        $this->assertInstanceOf(DriverOptions::class, $networkSetting->driverOpts);
        $this->assertNull($networkSetting->gwPriority);
        $this->assertSame('network-id', $networkSetting->id);
        $this->assertSame('endpoint-id', $networkSetting->endpointId);
        $this->assertInstanceOf(IPAddress::class, $networkSetting->gateway);
        $this->assertInstanceOf(CIDRBlock::class, $networkSetting->ipAddress);
        $this->assertNull($networkSetting->ipv6Gateway);
        $this->assertNull($networkSetting->ipv6Address);
        $this->assertSame([], $networkSetting->dnsNames);
    }

    /**
     * Tests the behavior of the `fromArray` method when provided with an invalid MAC address in the data array.
     */
    public function testFromArrayWithInvalidMacAddress(): void
    {
        $data = [
            'IPAMConfig' => null,
            'Links' => null,
            'MacAddress' => 'invalid-mac',
            'Aliases' => null,
            'DriverOpts' => null,
            'GwPriority' => null,
            'NetworkID' => 'network-id',
            'EndpointID' => 'endpoint-id',
            'Gateway' => '192.168.1.1',
            'IPAddress' => '192.168.1.100',
            'IPPrefixLen' => 24,
            'IPv6Gateway' => '',
            'GlobalIPv6Address' => '',
            'GlobalIPv6PrefixLen' => 0,
            'DNSNames' => null,
        ];

        $this->expectException(InvalidArgumentException::class);

        NetworkSetting::fromArray('test_network', $data);
    }
}
