<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Queries\Container;

use Graywings\DockerClient\Queries\Container\ContainersQuery;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function http_build_query;

/**
 * This class contains unit tests for the `ContainersQuery` class, which is used
 * to build and manage query parameters for Docker container-related API requests.
 * It validates the default values, initialization with custom values, and the
 * behavior of methods like `toArray` and `toQueryString`. Additionally, it ensures
 * that invalid inputs, such as an invalid limit, throw appropriate exceptions.
 */
#[CoversClass(ContainersQuery::class)]
final class ContainerQueryTest extends TestCase
{
    /**
     * Tests the default values of a `ContainersQuery` instance.
     *
     * This test ensures that when a `ContainersQuery` object is created without
     * any parameters, its properties are initialized to their default values:
     * - `all` is `false`
     * - `limit` is `null`
     * - `size` is `false`
     * - `filters` is an empty array
     */
    public function testDefaultValues(): void
    {
        $query = new ContainersQuery();

        $this->assertFalse($query->all);
        $this->assertNull($query->limit);
        $this->assertFalse($query->size);
        $this->assertSame([], $query->filters);
    }

    /**
     * Tests the initialization of a `ContainersQuery` instance with custom values.
     *
     * This test ensures that when a `ContainersQuery` object is created with specific
     * values for its properties, those values are correctly assigned and accessible.
     */
    public function testInitializationWithValues(): void
    {
        $filters = ['status' => ['running'], 'label' => 'env=production'];
        $query   = new ContainersQuery(
            all: true,
            limit: 10,
            size: true,
            filters: $filters,
        );

        $this->assertTrue($query->all);
        $this->assertSame(10, $query->limit);
        $this->assertTrue($query->size);
        $this->assertSame($filters, $query->filters);
    }

    /**
     * Tests the `toArray` method of the `ContainersQuery` class.
     *
     * This test ensures that the `toArray` method correctly converts the properties
     * of a `ContainersQuery` object into an associative array representation.
     */
    public function testToArray(): void
    {
        $filters = ['status' => ['running'], 'label' => 'env=production'];
        $query   = new ContainersQuery(
            all: true,
            limit: 5,
            size: true,
            filters: $filters,
        );

        $expected = [
            'all' => true,
            'size' => true,
            'limit' => 5,
            'filters' => $filters,
        ];

        $this->assertSame($expected, $query->toArray());
    }

    /**
     * Tests the `toQueryString` method of the `ContainersQuery` class.
     *
     * This test ensures that the `toQueryString` method correctly converts the
     * properties of a `ContainersQuery` object into a URL-encoded query string.
     */
    public function testToQueryString(): void
    {
        $filters = ['status' => ['running'], 'label' => 'env=production'];
        $query   = new ContainersQuery(
            all: true,
            limit: 5,
            size: true,
            filters: $filters,
        );

        $expected = http_build_query([
            'all' => true,
            'size' => true,
            'limit' => 5,
            'filters' => $filters,
        ]);

        $this->assertSame($expected, $query->toQueryString());
    }

    /**
     * Tests that an invalid limit value throws an exception.
     *
     * This test ensures that attempting to create a `ContainersQuery` object with
     * a `limit` value less than 1 results in an `InvalidArgumentException` with
     * the expected error message.
     */
    public function testInvalidLimitThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be greater than or equal to 1.');

        new ContainersQuery(limit: 0);
    }
}
