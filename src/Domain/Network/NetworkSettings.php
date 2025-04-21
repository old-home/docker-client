<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use Ramsey\Collection\Collection;

/**
 * NetworkSettings class
 *
 * This class represents the network settings of a Docker container. It encapsulates
 * the details of the container's network configuration, such as connected networks,
 * IP addresses, and other related settings.
 *
 * The `NetworkSettings` class provides a structured way to manage and access
 * network-related information for a container.
 */
final readonly class NetworkSettings
{
    /**
     * @param Collection<NetworkSetting> $networkSettings A collection of network settings, where each
     *                                     entry represents a specific network configuration
     *                                     for the container.
     */
    public function __construct(
        private(set) Collection $networkSettings,
    ) {
    }

    /**
     * @param array{
     *  Networks: array<string, array{
     *   IPAMConfig: array{
     *    IPv4Address: string,
     *    IPv6Address: string,
     *    LinkLocalIPs: string[]
     *   }|null,
     *   Links: string[]|null,
     *   MacAddress: string,
     *   Aliases: string[]|null,
     *   DriverOpts: array<string, string>|null,
     *   GwPriority?: int,
     *   NetworkID: string,
     *   EndpointID: string,
     *   Gateway: string,
     *   IPAddress: string,
     *   IPPrefixLen: int,
     *   IPv6Gateway: string,
     *   GlobalIPv6Address: string,
     *   GlobalIPv6PrefixLen: int,
     *   DNSNames: string[]|null
     *  }>
     * } $networkSettingsStdClass
     */
    public static function fromArray(array $networkSettingsStdClass): self
    {
        /**
         * @var Collection<NetworkSetting> $networkSettings
         */
        $networkSettings = new Collection(NetworkSetting::class, []);
        foreach ($networkSettingsStdClass['Networks'] as $key => $value) {
            $networkSettings->add(
                NetworkSetting::fromArray($key, $value),
            );
        }

        return new self(
            $networkSettings,
        );
    }
}
