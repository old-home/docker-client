<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

interface IContainerRepository
{
    public function getContainers(): Containers;
}
