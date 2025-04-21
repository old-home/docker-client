<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use InvalidArgumentException;

use function in_array;
use function str_starts_with;
use function substr;

/**
 * This class represents the network mode for a Docker container. It supports
 * predefined modes such as `host` and `none`, as well as dynamic modes like
 * `container:<id>`, where `<id>` is the ID of another container.
 */
readonly final class NetworkMode
{
    private const array VALID_MODES = ['host', 'none', 'container'];

    /**
     * @param string      $mode The network mode (e.g., "host", "none", or "container").
     * @param string|null $id   The container ID if the mode is "container", otherwise null.
     *
     * @throws InvalidArgumentException If the mode is invalid or the ID is required but missing.
     */
    public function __construct(
        private(set) string $mode,
        private(set) string|null $id = null,
    ) {
        if (! in_array($mode, self::VALID_MODES, true)) {
            throw new InvalidArgumentException('Invalid network mode: ' . $mode);
        }

        if (
            $mode === 'container'
            && (
                $id === null
                || $id === ''
            )
        ) {
            throw new InvalidArgumentException('Container ID is required for "container" mode.');
        }
    }

    /**
     * Parses a network mode string and creates a `NetworkMode` object.
     *
     * @param string $value The network mode string (e.g., "host", "none", or "container:<id>").
     *
     * @return self A new `NetworkMode` object.
     *
     * @throws InvalidArgumentException If the network mode string is invalid.
     */
    public static function parse(string $value): self
    {
        if (in_array($value, ['host', 'none'], true)) {
            return new self($value);
        }

        if (str_starts_with($value, 'container:')) {
            $id = substr($value, 10);
            if (empty($id)) {
                throw new InvalidArgumentException('Container ID is required for "container" mode.');
            }

            return new self('container', $id);
        }

        throw new InvalidArgumentException('Invalid network mode: ' . $value);
    }

    /**
     * Retrieves the network mode.
     *
     * @return string The network mode (e.g., "host", "none", or "container").
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Retrieves the container ID if the mode is "container".
     *
     * @return string|null The container ID, or null if the mode is not "container".
     */
    public function getId(): string|null
    {
        return $this->id;
    }

    /**
     * Converts the network mode to its string representation.
     *
     * @return string The string representation of the network mode.
     */
    public function __toString(): string
    {
        if ($this->mode === 'container') {
            return 'container:' . ($this->id ?? '');
        }

        return $this->mode;
    }
}
