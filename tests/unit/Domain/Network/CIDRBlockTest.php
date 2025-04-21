<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\CIDRBlock;
use Graywings\DockerClient\Domain\Network\IPAddress;
use Graywings\DockerClient\Domain\Network\IPVersion;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the CIDRBlock class, which represents
 * a CIDR block and provides methods for parsing and validating CIDR strings.
 */
#[CoversClass(CIDRBlock::class)]
#[CoversClass(IPAddress::class)]
#[CoversClass(IPVersion::class)]
final class CIDRBlockTest extends TestCase
{
    /**
     * Tests parsing a valid IPv4 CIDR block.
     */
    public function testParseIPv4(): void
    {
        $cidr = CIDRBlock::parse('192.168.1.0/24');
        $this->assertSame('192.168.1.0/24', (string) $cidr);
        $this->assertSame(IPVersion::IPV4, $cidr->ipVersion);
        $this->assertSame(24, $cidr->prefix);
    }

    /**
     * Tests parsing a valid IPv6 CIDR block.
     */
    public function testParseIPv6(): void
    {
        $cidr = CIDRBlock::parse('2001:db8::/32');
        $this->assertSame('2001:db8::/32', (string) $cidr);
        $this->assertSame(IPVersion::IPV6, $cidr->ipVersion);
        $this->assertSame(32, $cidr->prefix);
    }

    /**
     * Tests parsing an invalid CIDR string.
     */
    public function testParseInvalidCIDR(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid CIDR format');
        CIDRBlock::parse('invalid-cidr');
    }

    /**
     * Tests parsing an IPv4 CIDR block with an invalid prefix length.
     */
    public function testParseInvalidPrefix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid prefix length for IPv4: 33');
        CIDRBlock::parse('192.168.1.0/33');
    }

    /**
     * Tests parsing an IPv6 CIDR block with an invalid prefix length.
     */
    public function testParseInvalidIPv6Prefix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid prefix length for IPv6: 129');
        CIDRBlock::parse('2001:db8::/129');
    }

    /**
     * Tests parsing a CIDR block with a non-numeric prefix.
     */
    public function testParseNonNumericPrefix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid prefix length for IPv4: invalid');
        CIDRBlock::parse('192.168.1.0/invalid');
    }

    /**
     * Tests parsing a CIDR block with a missing prefix length.
     */
    public function testParseMissingPrefix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid CIDR format. Missing prefix length: 192.168.1.0');
        CIDRBlock::parse('192.168.1.0');
    }

    /**
     * Tests parsing an empty CIDR string.
     */
    public function testParseEmptyCIDR(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid CIDR format. Missing prefix length: ');
        CIDRBlock::parse('');
    }

    /**
     * Tests retrieving the start and end IP addresses of a CIDR block.
     */
    public function testStartAndEnd(): void
    {
        $cidr = CIDRBlock::parse('192.168.1.0/24');
        $this->assertSame('192.168.1.0', (string) $cidr->start());
        $this->assertSame('192.168.1.255', (string) $cidr->end());
    }

    /**
     * Tests retrieving the range of IP addresses in a CIDR block.
     */
    public function testRange(): void
    {
        $cidr  = CIDRBlock::parse('192.168.1.0/30');
        $range = $cidr->range();
        $this->assertCount(2, $range);
        $this->assertSame('192.168.1.0', (string) $range[0]);
        $this->assertSame('192.168.1.3', (string) $range[1]);
    }

    /**
     * Tests whether a CIDR block contains specific IP addresses.
     */
    public function testContains(): void
    {
        $cidr = CIDRBlock::parse('192.168.1.0/24');
        $this->assertTrue($cidr->contains(IPAddress::parse('192.168.1.1')));
        $this->assertTrue($cidr->contains(IPAddress::parse('192.168.1.254')));
        $this->assertFalse($cidr->contains(IPAddress::parse('192.168.2.1')));
    }

    /**
     * Tests whether a CIDR block contains an IP address with a different IP version.
     */
    public function testContainsWithDifferentIPVersion(): void
    {
        $cidr = CIDRBlock::parse('192.168.1.0/24');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP Version');
        $cidr->contains(IPAddress::parse('2001:db8::1'));
    }

    /**
     * Provides invalid IP addresses for testing.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideInvalidIPAddresses(): array
    {
        return [
            'IPv4: 256.256.256.256' => ['256.256.256.256/24', 'Invalid CIDR format'],
            'IPv4: 192.168.1.256' => ['192.168.1.256/24', 'Invalid CIDR format'],
            'IPv4: 192.168.256.1' => ['192.168.256.1/24', 'Invalid CIDR format'],
            'IPv4: 192.256.1.1' => ['192.256.1.1/24', 'Invalid CIDR format'],
            'IPv4: 256.168.1.1' => ['256.168.1.1/24', 'Invalid CIDR format'],
            'IPv6: 2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3' => [
                '2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3/64',
                'Invalid CIDR format',
            ],
            'IPv6: 2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3:85a3' => [
                '2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3:85a3/64',
                'Invalid CIDR format',
            ],
            'IPv6: 2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3:85a3:85a3' => [
                '2001:db8:85a3:85a3:85a3:85a3:85a3:85a3:85a3:85a3:85a3/64',
                'Invalid CIDR format',
            ],
        ];
    }

    /**
     * Tests parsing invalid IP addresses using a data provider.
     *
     * @param string $cidr            The CIDR string to parse.
     * @param string $expectedMessage The expected exception message.
     */
    #[DataProvider('provideInvalidIPAddresses')]
    public function testParseInvalidIPAddresses(string $cidr, string $expectedMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);
        CIDRBlock::parse($cidr);
    }
}
