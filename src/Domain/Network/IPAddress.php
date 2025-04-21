<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;

use function inet_ntop;
use function inet_pton;
use function strlen;

/**
 * This class represents an IP address and its associated IP version (IPv4 or IPv6).
 * It provides methods for parsing, comparing, and converting IP addresses to their
 * string representation.
 *
 * The class ensures that IP addresses are handled in a strongly-typed and consistent
 * manner, reducing the risk of errors when working with network-related data.
 */
final readonly class IPAddress
{
    /**
     * @param string    $address   The binary representation of the IP address.
     * @param IPVersion $ipVersion The IP version (IPv4 or IPv6) of the address.
     */
    public function __construct(
        public string $address,
        public IPVersion $ipVersion,
    ) {
    }

    /**
     * Compares the current IP address with another IP address.
     *
     * @param self $other The IP address to compare with.
     *
     * @return bool True if the addresses are equal, false otherwise.
     */
    public function equals(self $other): bool
    {
        return $this->address === $other->address;
    }

    /**
     * Parses a string representation of an IP address and creates an `IPAddress` object.
     *
     * This method validates the IP address format and determines its IP version (IPv4 or IPv6).
     *
     * @param string $ip The string representation of the IP address (e.g., "192.168.1.1").
     *
     * @return self A new `IPAddress` object representing the parsed IP address.
     *
     * @throws InvalidArgumentException If the IP address format is invalid.
     */
    public static function parse(string $ip): self
    {
        if (empty($ip)) {
            throw new InvalidArgumentException('Invalid IP address format: ');
        }

        $binary = inet_pton($ip);
        if ($binary === false) {
            throw new InvalidArgumentException('Invalid IP address format: ' . $ip);
        }

        return new self(
            $binary,
            strlen($binary) === 4 ? IPVersion::IPV4 : IPVersion::IPV6,
        );
    }

    /**
     * Converts the IP address to its string representation.
     *
     * @return string The string representation of the IP address (e.g., "192.168.1.1").
     */
    public function __toString(): string
    {
        $ip = inet_ntop($this->address);
        // @codeCoverageIgnoreStart
        if ($ip === false) {
            throw new InvalidArgumentException('Invalid IP address format: ' . $this->address);
        }

        // @codeCoverageIgnoreEnd

        return $ip ?: '';
    }
}
