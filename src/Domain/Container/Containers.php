<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

use Ramsey\Collection\Collection;
use stdClass;

use function array_map;
use function json_decode;

readonly final class Containers
{
    /** @param Collection<Container> $containers */
    public function __construct(
        private(set) Collection $containers,
    ) {
    }

    public static function fromJson(string $containersJson): self
    {
        return new self(
            new Collection(
                Container::class,
                array_map(
                    static function (stdClass $containerAssociate) {
                        return Container::fromStdClass($containerAssociate);
                    },
                    json_decode($containersJson),
                ),
            ),
        );
    }

    /** @return Container[] */
    public function toArray(): array
    {
        return $this->containers->toArray();
    }
}
