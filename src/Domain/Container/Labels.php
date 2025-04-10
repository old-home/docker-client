<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use stdClass;

use function array_map;

final readonly class Labels
{
    /** @param array<string, string> $labels */
    public function __construct(private(set) array $labels)
    {
    }

    public static function fromStdClass(stdClass $object): self
    {
        $array = array_map(static function ($value) {
            return $value;
        }, (array) $object);

        return new self($array);
    }
}
