<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\PortNumber;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the PortNumber class, which represents
 * a network port number. It validates that the port number is within the
 * valid range (0-65535) and ensures that invalid values throw appropriate
 * exceptions.
 */
#[CoversClass(PortNumber::class)]
final class PortNumberTest extends TestCase
{
    /**
     * Tests creating a valid PortNumber object with a typical port number.
     *
     * This test ensures that a PortNumber object can be created with a valid
     * port number (e.g., 80) and that the value is correctly stored.
     */
    public function testCreateValidPortNumber(): void
    {
        $portNumber = new PortNumber(80);
        $this->assertSame(80, $portNumber->value);
    }

    /**
     * Tests creating a PortNumber object with the minimum valid value (0).
     *
     * This test ensures that a PortNumber object can be created with the
     * minimum valid port number (0) and that the value is correctly stored.
     */
    public function testCreatePortNumberWithZero(): void
    {
        $portNumber = new PortNumber(0);
        $this->assertSame(0, $portNumber->value);
    }

    /**
     * Tests creating a PortNumber object with the maximum valid value (65535).
     *
     * This test ensures that a PortNumber object can be created with the
     * maximum valid port number (65535) and that the value is correctly stored.
     */
    public function testCreatePortNumberWithMaxValue(): void
    {
        $portNumber = new PortNumber(65535);
        $this->assertSame(65535, $portNumber->value);
    }

    /**
     * Tests creating a PortNumber object with a negative value.
     *
     * This test ensures that attempting to create a PortNumber object with
     * a negative value throws an InvalidArgumentException with the expected
     * error message.
     */
    public function testCreatePortNumberWithNegativeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PortNumber value must be between 0 and 65535');
        new PortNumber(-1);
    }

    /**
     * Tests creating a PortNumber object with a value greater than the maximum.
     *
     * This test ensures that attempting to create a PortNumber object with
     * a value greater than 65535 throws an InvalidArgumentException with the
     * expected error message.
     */
    public function testCreatePortNumberWithTooLargeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PortNumber value must be between 0 and 65535');
        new PortNumber(65536);
    }
}
