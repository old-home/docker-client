<?php

declare(strict_types=1);

use Graywings\DockerClient\Domain\Container\IContainerRepository;
use Graywings\DockerClient\Tests\Mock\JsonContainerRepository;

use function DI\create;

return [
    IContainerRepository::class => create(JsonContainerRepository::class)
        ->constructor(__DIR__ . '/../fixtures/containers.json'),
];
