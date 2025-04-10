<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

enum IPVersion: string
{
    case IPV4 = 'IPv4';
    case IPV6 = 'IPv6';

    public function bitLength(): int
    {
        return match ($this) {
            self::IPV4 => 32,
            self::IPV6 => 128
        };
    }

    public function byteLength(): int
    {
        return $this->bitLength() / 8;
    }
}
