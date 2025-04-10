<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

enum ContainerState: string
{
    case Created    = 'created';
    case Restarting = 'restarting';
    case Running    = 'running';
    case Removing   = 'removing';
    case Paused     = 'paused';
    case Exited     = 'exited';
    case Dead       = 'dead';
}
