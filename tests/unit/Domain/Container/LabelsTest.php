<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\Labels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * This class contains unit tests for the `Labels` class, which represents
 * a collection of key-value pairs (labels) associated with a Docker container.
 * It validates the creation of `Labels` objects from arrays instances, as
 * well as handling empty labels.
 */
#[CoversClass(Labels::class)]
final class LabelsTest extends TestCase
{
    /**
     * Tests creating a `Labels` object from an associative array.
     *
     * This test ensures that a `Labels` object can be instantiated with
     * an associative array of key-value pairs and that the labels are
     * correctly stored and accessible.
     */
    public function testCreateLabelsFromArray(): void
    {
        $labels       = ['key1' => 'value1', 'key2' => 'value2'];
        $labelsObject = new Labels($labels);

        $this->assertSame($labels, $labelsObject->labels);
    }

    /**
     * Tests creating a `Labels` object with an empty array.
     *
     * This test ensures that a `Labels` object can be instantiated with
     * an empty array and that the labels property is correctly set to an
     * empty array.
     */
    public function testEmptyLabels(): void
    {
        $labels = new Labels([]);
        $this->assertSame([], $labels->labels);
    }
}
