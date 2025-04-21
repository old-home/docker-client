<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;

use function array_slice;
use function explode;
use function implode;
use function preg_match;
use function strtoupper;

/**
 * This class represents a MAC address and provides functionality to parse,
 * validate, and manage its components. A MAC address is divided into two parts:
 * - OUI (Organizationally Unique Identifier): The first 3 bytes, identifying the vendor.
 * - Device ID: The last 3 bytes, uniquely identifying the device within the vendor's range.
 *
 * The class ensures that the MAC address is valid and provides methods to parse
 * and split it into its components.
 */
final readonly class MacAddress
{
    /**
     * @param string $oui      OUI (Organizationally Unique Identifier) part of the MAC address.
     * @param string $deviceId The device ID part of the MAC address.
     */
    public function __construct(
        private(set) string $oui,
        private(set) string $deviceId,
    ) {
    }

    /**
     * Parses a MAC address string and creates a `MacAddress` object.
     *
     * This method validates the given MAC address string and splits it into
     * its components: OUI and Device ID. If the MAC address is invalid, an
     * exception is thrown.
     *
     * @param string $macAddress The MAC address to parse.
     *
     * @return self A new `MacAddress` object containing the parsed components.
     *
     * @throws InvalidArgumentException If the MAC address is invalid.
     */
    public static function parse(string $macAddress): self
    {
        if (! self::isValidMacAddress($macAddress)) {
            throw new InvalidArgumentException('Invalid MAC address: ' . $macAddress);
        }

        [$oui, $deviceId] = self::splitMacAddress($macAddress);

        return new self($oui, $deviceId);
    }

    /**
     * Validates the MAC address format.
     *
     * This method checks if the given MAC address string matches the standard
     * MAC address format (e.g., XX:XX:XX:XX:XX:XX).
     *
     * @param string $macAddress The MAC address to validate.
     *
     * @return bool True if the MAC address is valid, false otherwise.
     */
    private static function isValidMacAddress(string $macAddress): bool
    {
        return (bool) preg_match('/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/', $macAddress);
    }

    /**
     * Splits the MAC address into OUI and Device ID.
     *
     * This method divides the MAC address into its two main components:
     * - OUI (Organizationally Unique Identifier): The first 3 bytes.
     * - Device ID: The last 3 bytes.
     *
     * @param string $macAddress The MAC address to split.
     *
     * @return string[] An array containing the OUI and Device ID.
     */
    private static function splitMacAddress(string $macAddress): array
    {
        $parts    = explode(':', $macAddress);
        $oui      = strtoupper(implode(':', array_slice($parts, 0, 3))); // First 3 bytes
        $deviceId = strtoupper(implode(':', array_slice($parts, 3, 3))); // Last 3 bytes

        return [$oui, $deviceId];
    }

    /**
     * Converts the MAC address object back to its original string representation.
     *
     * @return string The full MAC address in the format XX:XX:XX:XX:XX:XX.
     */
    public function __toString(): string
    {
        return $this->oui . ':' . $this->deviceId;
    }
}
