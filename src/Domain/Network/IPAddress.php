<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;

use function inet_ntop;
use function inet_pton;
use function strlen;

final readonly class IPAddress
{
    public function __construct(
        public string $address,
        public IPVersion $ipVersion,
    ) {
    }

    public function equals(self $other): bool
    {
        return $this->address === $other->address;
    }

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

    public function __toString(): string
    {
        return inet_ntop($this->address) ?: '';
    }
}
