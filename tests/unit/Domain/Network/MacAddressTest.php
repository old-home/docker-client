<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\MacAddress;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the `MacAddress` class. It verifies the
 * functionality of parsing, validating, and managing MAC addresses, including
 * splitting them into OUI and Device ID components, and ensuring proper string
 * representation.
 */
#[CoversClass(MacAddress::class)]
final class MacAddressTest extends TestCase
{
    /**
     * Tests that a valid MAC address is correctly parsed into OUI and Device ID.
     *
     * This test ensures that the `parse` method correctly splits a valid MAC
     * address into its OUI and Device ID components, and that the `__toString`
     * method returns the original MAC address string.
     */
    public function testParseValidMacAddress(): void
    {
        $mac = MacAddress::parse('00:1A:2B:3C:4D:5E');

        $this->assertSame('00:1A:2B', $mac->oui);
        $this->assertSame('3C:4D:5E', $mac->deviceId);
        $this->assertSame('00:1A:2B:3C:4D:5E', (string) $mac);
    }

    /**
     * Tests that an invalid MAC address throws an exception.
     *
     * This test ensures that the `parse` method throws an `InvalidArgumentException`
     * when an invalid MAC address is provided.
     */
    public function testParseInvalidMacAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid MAC address: 00:1A:2B:3C:4D');

        MacAddress::parse('00:1A:2B:3C:4D'); // Invalid format
    }

    /**
     * Tests that the `__toString` method returns the correct MAC address string.
     *
     * This test ensures that the `__toString` method correctly reconstructs
     * the original MAC address string from the OUI and Device ID components.
     */
    public function testToString(): void
    {
        $mac = MacAddress::parse('00:1A:2B:3C:4D:5E');

        $this->assertSame('00:1A:2B:3C:4D:5E', (string) $mac);
    }

    /**
     * Tests that a MAC address with lowercase letters is correctly normalized to uppercase.
     *
     * This test ensures that the `parse` method normalizes MAC addresses with
     * lowercase letters to uppercase, and that the OUI and Device ID components
     * are correctly extracted.
     */
    public function testParseLowercaseMacAddress(): void
    {
        $mac = MacAddress::parse('00:1a:2b:3c:4d:5e');

        $this->assertSame('00:1A:2B', $mac->oui);
        $this->assertSame('3C:4D:5E', $mac->deviceId);
        $this->assertSame('00:1A:2B:3C:4D:5E', (string) $mac);
    }
}
