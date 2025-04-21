<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use function array_key_exists;

/**
 * This class represents the network settings of a Docker container. It encapsulates
 * various properties related to the network configuration, including IP addresses,
 * gateways, DNS names, and other network-related details.
 *
 * The `NetworkSetting` class provides a structured way to manage and parse network
 * configuration data retrieved from the Docker API.
 */
final readonly class NetworkSetting
{
    /**
     * @param string          $name        The name of the network to which the container is connected.
     * @param IpamConfig|null $ipamConfig  The IPAM (IP Address Management) configuration for the network.
     * @param string[]        $links       An array of links between containers in the network.
     * @param MacAddress      $macAddress  The MAC address assigned to the container in the network.
     * @param string[]        $aliases     An array of aliases assigned to the container in the network.
     * @param DriverOptions   $driverOpts  Driver-specific options for the network.
     * @param int|null        $gwPriority  The gateway priority for the network.
     * @param string          $id          The unique identifier of the network.
     * @param string          $endpointId  The unique identifier of the network endpoint.
     * @param IPAddress|null  $gateway     The IPv4 gateway address for the network.
     * @param CIDRBlock|null  $ipAddress   The IPv4 address and subnet mask assigned to the container.
     * @param IPAddress|null  $ipv6Gateway The IPv6 gateway address for the network.
     * @param CIDRBlock|null  $ipv6Address The IPv6 address and subnet mask assigned to the container.
     * @param string[]        $dnsNames    An array of DNS names associated with the network.
     */
    public function __construct(
        private(set) string $name,
        private(set) IpamConfig|null $ipamConfig,
        private(set) array $links,
        private(set) MacAddress $macAddress,
        private(set) array $aliases,
        private(set) DriverOptions $driverOpts,
        private(set) int|null $gwPriority,
        private(set) string $id,
        private(set) string $endpointId,
        private(set) IPAddress|null $gateway,
        private(set) CIDRBlock|null $ipAddress,
        private(set) IPAddress|null $ipv6Gateway,
        private(set) CIDRBlock|null $ipv6Address,
        private(set) array $dnsNames,
    ) {
    }

    /**
     * Creates a `NetworkSetting` object from an associate array.
     *
     * This method parses an associate array (typically retrieved from the Docker Engine API)
     * and extracts the network configuration details to initialize a `NetworkSetting` instance.
     *
     * @param array{
     *  IPAMConfig: array{
     *   IPv4Address: string,
     *   IPv6Address: string,
     *   LinkLocalIPs: string[]
     *  }|null,
     *  Links: string[]|null,
     *  MacAddress: string,
     *  Aliases: string[]|null,
     *  DriverOpts: array<string, string>|null,
     *  GwPriority?: int,
     *  NetworkID: string,
     *  EndpointID: string,
     *  Gateway: string,
     *  IPAddress: string,
     *  IPPrefixLen: int,
     *  IPv6Gateway: string,
     *  GlobalIPv6Address: string,
     *  GlobalIPv6PrefixLen: int,
     *  DNSNames: string[]|null
     * } $networkSettingArray
     *
     * @return self A new `NetworkSetting` associate array populated with the parsed data.
     */
    public static function fromArray(string $name, array $networkSettingArray): self
    {
        return new self(
            $name,
            $networkSettingArray['IPAMConfig'] !== null
                ? IpamConfig::fromArray($networkSettingArray['IPAMConfig'])
                : null,
            $networkSettingArray['Links'] ?? [],
            MacAddress::parse($networkSettingArray['MacAddress']),
            $networkSettingArray['Aliases'] ?? [],
            DriverOptions::fromArray($networkSettingArray['DriverOpts']),
            array_key_exists('GwPriority', $networkSettingArray)
                ? $networkSettingArray['GwPriority'] : null,
            $networkSettingArray['NetworkID'],
            $networkSettingArray['EndpointID'],
            IPAddress::parse($networkSettingArray['Gateway']),
            CIDRBlock::parse($networkSettingArray['IPAddress'] . '/' . $networkSettingArray['IPPrefixLen']),
            $networkSettingArray['IPv6Gateway'] === ''
                ? null
                : IPAddress::parse($networkSettingArray['IPv6Gateway']),
            $networkSettingArray['GlobalIPv6Address'] === ''
                ? null
                : CIDRBlock::parse(
                    $networkSettingArray['GlobalIPv6Address'] . '/' . $networkSettingArray['GlobalIPv6PrefixLen'],
                ),
            $networkSettingArray['DNSNames'] ?? [],
        );
    }
}
