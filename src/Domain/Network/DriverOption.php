<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

/**
 * Represents a specific driver option used within a network driver context.
 * This class encapsulates a network driver and its associated configuration option.
 *
 * The `DriverOption` class is immutable and ensures that instances cannot be modified
 * after creation. It provides a factory method for constructing instances from string
 * representations of the driver and option.
 */
readonly final class DriverOption
{
    /**
     * Constructor method.
     *
     * @param NetworkDriver $driver The network driver associated with the option.
     * @param string        $option The configuration option for the network driver.
     */
    public function __construct(
        private(set) NetworkDriver $driver,
        private(set) string $option,
    ) {
    }

    /**
     * Creates an instance of the class from the provided string values.
     *
     * @param string $driver The name of the network driver in string format.
     * @param string $option A configuration option for the network driver in string format.
     *
     * @return self Returns a new instance of the class initialized with the given parameters.
     */
    public static function fromStrings(string $driver, string $option): self
    {
        return new self(
            NetworkDriver::from($driver),
            $option,
        );
    }
}
