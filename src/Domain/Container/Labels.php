<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

/**
 * This class represents a collection of key-value pairs (labels) associated
 * with a Docker container. Labels are metadata that can be used to organize
 * and manage containers more effectively.
 *
 * The class provides methods to initialize labels and convert raw data
 * (e.g., from a `stdClass` object) into a structured `Labels` object.
 */
final readonly class Labels
{
    /**
     * @param array<string, string> $labels An associative array of labels where
     *                                      the key is the label name and the value
     *                                      is the label value.
     */
    public function __construct(private(set) array $labels)
    {
    }

    /**
     * Creates a `Labels` object from a `stdClass` object.
     *
     * This method converts a `stdClass` object (typically retrieved from the
     * Docker API) into an associative array of labels and initializes a `Labels`
     * object with the resulting array.
     *
     * @param array<string, string> $labelsArray A `stdClass` object representing the raw label data.
     *
     * @return self A new `Labels` object containing the parsed labels.
     */
    public static function fromArray(array $labelsArray): self
    {
        return new self($labelsArray);
    }
}
