<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

/**
 * Enum IPVersion
 *
 * This enum represents the IP version, either IPv4 or IPv6. It provides a
 * strongly-typed way to handle IP versions and includes utility methods
 * for retrieving the bit length and byte length of each version.
 */
enum IPVersion: string
{
    case IPV4 = 'IPv4';
    case IPV6 = 'IPv6';

    /**
     * Retrieves the bit length of the IP version.
     *
     * IPv4 has a length of 32 a bit, while IPv6 has a length of 128 a bit.
     *
     * @return int The bit length of the IP version.
     */
    public function bitLength(): int
    {
        return match ($this) {
            self::IPV4 => 32,
            self::IPV6 => 128
        };
    }

    /**
     * Retrieves the byte length of the IP version.
     *
     * The byte length is calculated by dividing the bit length by 8.
     * IPv4 has a byte length of 4, while IPv6 has a byte length of 16.
     *
     * @return int The byte length of the IP version.
     */
    public function byteLength(): int
    {
        return match ($this) {
            self::IPV4 => 4,
            self::IPV6 => 16,
        };
    }
}
