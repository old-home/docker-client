<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;

/**
 * This class represents a network port number. It ensures that the port number
 * is within the valid range (0 to 65535) and provides a strongly-typed way to
 * handle port numbers in network-related configurations.
 */
readonly final class PortNumber
{
    /**
     * @param int $value The port number value.
     *
     * @throws InvalidArgumentException If the port number is not within the valid range (0 to 65535).
     */
    public function __construct(private(set) int $value)
    {
        if ($value < 0 || $value > 65535) {
            throw new InvalidArgumentException('PortNumber value must be between 0 and 65535');
        }
    }
}
