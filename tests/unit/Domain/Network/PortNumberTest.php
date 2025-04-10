<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\PortNumber;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PortNumber::class)]
final class PortNumberTest extends TestCase
{
    public function testCreateValidPortNumber(): void
    {
        $portNumber = new PortNumber(80);
        $this->assertSame(80, $portNumber->value);
    }

    public function testCreatePortNumberWithZero(): void
    {
        $portNumber = new PortNumber(0);
        $this->assertSame(0, $portNumber->value);
    }

    public function testCreatePortNumberWithMaxValue(): void
    {
        $portNumber = new PortNumber(65535);
        $this->assertSame(65535, $portNumber->value);
    }

    public function testCreatePortNumberWithNegativeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PortNumber value must be between 0 and 65535');
        new PortNumber(-1);
    }

    public function testCreatePortNumberWithTooLargeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PortNumber value must be between 0 and 65535');
        new PortNumber(65536);
    }
}
