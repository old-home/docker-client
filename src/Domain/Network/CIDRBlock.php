<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;

use function chr;
use function count;
use function explode;
use function inet_ntop;
use function inet_pton;
use function is_numeric;
use function ord;
use function str_repeat;
use function strlen;

/**
 * This class represents a CIDR (Classless Inter-Domain Routing) block, which
 * is used to define IP address ranges. It provides methods for parsing CIDR
 * strings, calculating the start and end IP addresses of the range, checking
 * if an IP address is within the range, and generating a subnet mask.
 *
 * The class supports both IPv4 and IPv6 CIDR blocks.
 */
final readonly class CIDRBlock
{
    /**
     * @param string    $network   The binary representation of the network address.
     * @param int       $prefix    The prefix length of the CIDR block (e.g., 24 for IPv4).
     * @param IPVersion $ipVersion The IP version (IPv4 or IPv6) of the CIDR block.
     */
    public function __construct(
        public string $network,
        public int $prefix,
        public IPVersion $ipVersion,
    ) {
    }

    /**
     * Parses a CIDR string and creates a `CIDRBlock` object.
     *
     * This method validates the CIDR string, extracts the network address and
     * prefix length, and determines the IP version (IPv4 or IPv6).
     *
     * @param string $cidr The CIDR string to parse (e.g., "192.168.1.0/24").
     *
     * @return self A new `CIDRBlock` object representing the parsed CIDR block.
     *
     * @throws InvalidArgumentException If the CIDR string is invalid.
     */
    public static function parse(string $cidr): self
    {
        if (empty($cidr)) {
            throw new InvalidArgumentException('Invalid CIDR format. Missing prefix length: ');
        }

        $parts = explode('/', $cidr);
        if (count($parts) !== 2) {
            throw new InvalidArgumentException('Invalid CIDR format. Missing prefix length: ' . $cidr);
        }

        [$network, $prefix] = $parts;
        $binary             = inet_pton($network);
        if ($binary === false) {
            throw new InvalidArgumentException('Invalid CIDR format');
        }

        $ipVersion = strlen($binary) === 4 ? IPVersion::IPV4 : IPVersion::IPV6;
        $maxPrefix = $ipVersion === IPVersion::IPV4 ? 32 : 128;

        if (! is_numeric($prefix)) {
            throw new InvalidArgumentException(
                'Invalid prefix length for '
                    . ($ipVersion === IPVersion::IPV4 ? 'IPv4' : 'IPv6')
                    . ': ' . $prefix,
            );
        }

        $prefix = (int) $prefix;
        if ($prefix < 0 || $prefix > $maxPrefix) {
            throw new InvalidArgumentException(
                'Invalid prefix length for '
                    . ($ipVersion === IPVersion::IPV4 ? 'IPv4' : 'IPv6')
                    . ': ' . $prefix,
            );
        }

        return new self($binary, $prefix, $ipVersion);
    }

    /**
     * Retrieves the start IP address of the CIDR block.
     *
     * @return IPAddress The start IP address of the range.
     */
    public function start(): IPAddress
    {
        return new IPAddress($this->network, $this->ipVersion);
    }

    /**
     * Retrieves the end IP address of the CIDR block.
     *
     * This method calculates the end IP address by applying the subnet mask
     * to the network address and setting the remaining bits to 1.
     *
     * @return IPAddress The end IP address of the range.
     */
    public function end(): IPAddress
    {
        $mask = $this->createMask();
        $end  = $this->network;
        for ($i = strlen($end) - 1; $i >= 0; $i--) {
            $end[$i] = chr(ord($end[$i]) | ~ord($mask[$i]) & 0xFF);
        }

        return new IPAddress($end, $this->ipVersion);
    }

    /**
     * Retrieves the range of IP addresses in the CIDR block.
     *
     * @return IPAddress[] An array containing the start and end IP addresses.
     */
    public function range(): array
    {
        return [$this->start(), $this->end()];
    }

    /**
     * Checks if a given IP address is within the CIDR block.
     *
     * @param IPAddress $ip The IP address to check.
     *
     * @return bool True if the IP address is within the range, false otherwise.
     *
     * @throws InvalidArgumentException If the IP version of the address does not match the CIDR block.
     */
    public function contains(IPAddress $ip): bool
    {
        if ($ip->ipVersion !== $this->ipVersion) {
            throw new InvalidArgumentException('Invalid IP Version');
        }

        $mask    = $this->createMask();
        $network = $this->network;
        $address = $ip->address;

        for ($i = 0; $i < strlen($network); $i++) {
            if ((ord($network[$i]) & ord($mask[$i])) !== (ord($address[$i]) & ord($mask[$i]))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Creates a subnet mask based on the prefix length.
     *
     * @return string The binary representation of the subnet mask.
     */
    private function createMask(): string
    {
        $bytes  = strlen($this->network);
        $mask   = str_repeat("\0", $bytes);
        $prefix = $this->prefix;

        for ($i = 0; $i < $bytes; $i++) {
            if ($prefix < 8) {
                $mask[$i] = chr(0xFF << 8 - $prefix);
                break;
            }

            $mask[$i] = "\xFF";
            $prefix  -= 8;
        }

        return $mask;
    }

    /**
     * Converts the CIDR block to its string representation.
     *
     * @return string The CIDR block in string format (e.g., "192.168.1.0/24").
     */
    public function __toString(): string
    {
        $ipString = inet_ntop($this->network);
        // @codeCoverageIgnoreStart
        if ($ipString === false) {
            throw new InvalidArgumentException('Invalid IP address format');
        }

        // @codeCoverageIgnoreEnd

        return $ipString . '/' . $this->prefix;
    }
}
