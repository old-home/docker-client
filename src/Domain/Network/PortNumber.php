<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;

readonly final class PortNumber
{
    public function __construct(private(set) int $value)
    {
        if ($value < 0 || $value > 65535) {
            throw new InvalidArgumentException('PortNumber value must be between 0 and 65535');
        }
    }
}
