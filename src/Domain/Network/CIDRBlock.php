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

final readonly class CIDRBlock
{
    public function __construct(
        public string $network,
        public int $prefix,
        public IPVersion $ipVersion,
    ) {
    }

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

    public function start(): IPAddress
    {
        return new IPAddress($this->network, $this->ipVersion);
    }

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
     * @return IPAddress[]
     */
    public function range(): array
    {
        return [$this->start(), $this->end()];
    }

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

    public function __toString(): string
    {
        return inet_ntop($this->network) . '/' . $this->prefix;
    }
}
