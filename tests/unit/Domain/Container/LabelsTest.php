<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Container;

use Graywings\DockerClient\Domain\Container\Labels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(Labels::class)]
final class LabelsTest extends TestCase
{
    public function testCreateLabelsFromArray(): void
    {
        $labels       = ['key1' => 'value1', 'key2' => 'value2'];
        $labelsObject = new Labels($labels);

        $this->assertEquals($labels, $labelsObject->labels);
    }

    public function testCreateLabelsFromStdClass(): void
    {
        $stdClass       = new stdClass();
        $stdClass->key1 = 'value1';
        $stdClass->key2 = 'value2';

        $labels = Labels::fromStdClass($stdClass);

        $this->assertEquals(['key1' => 'value1', 'key2' => 'value2'], $labels->labels);
    }

    public function testEmptyLabels(): void
    {
        $labels = new Labels([]);
        $this->assertEquals([], $labels->labels);
    }
}
