<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use Ramsey\Collection\Collection;

/**
 * A class representing driver options, which are initialized from a collection
 * or an array input. This class is immutable and ensures proper structure
 * when handling driver options.
 */
final readonly class DriverOptions
{
    /**
     * Constructor method.
     *
     * @param Collection<DriverOption> $options A collection of options to initialize the instance.
     *
     * @return void
     */
    public function __construct(
        private(set) Collection $options,
    ) {
    }

    /**
     * Creates an instance of the class from an array of driver options.
     *
     * @param array<string, string>|null $driverOptionsArray An array of driver options or null.
     *
     * @return self An instance of the class populated with the given driver options.
     */
    public static function fromArray(array|null $driverOptionsArray): self
    {
        /**
         * @var Collection<DriverOption> $collection
         */
        $collection = new Collection(DriverOption::class, []);
        if ($driverOptionsArray === null) {
            return new self($collection);
        }

        foreach ($driverOptionsArray as $key => $driverOptionArray) {
            $collection->add(DriverOption::fromStrings($key, $driverOptionArray));
        }

        return new self($collection);
    }
}
