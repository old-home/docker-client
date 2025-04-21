<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Container;

/**
 * Enum ContainerState
 *
 * This enum represents the various states a Docker container can be in.
 * It provides a strongly-typed way to handle container states, ensuring
 * consistency and reducing the risk of errors when working with container
 * state values.
 *
 * The states include:
 * - `Created`: The container is created but not started.
 * - `Restarting`: The container is restarting.
 * - `Running`: The container is currently running.
 * - `Removing`: The container is in the process of being removed.
 * - `Paused`: The container is paused.
 * - `Exited`: The container has stopped running.
 * - `Dead`: The container is in a dead state.
 */
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
