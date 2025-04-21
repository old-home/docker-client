<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Queries\Container;

use InvalidArgumentException;

use function http_build_query;

/**
 * This class represents a query for retrieving Docker container information.
 * It encapsulates query parameters such as whether to show all containers,
 * limit the number of results, include container sizes, and apply specific filters.
 *
 * The class provides methods to convert the query into array or URL-encoded
 * query string formats, making it easy to use with HTTP requests to the Docker API.
 */
final readonly class ContainersQuery
{
    /**
     * @param bool     $all   Show all containers. Default is false, meaning only running containers are shown.
     * @param int|null $limit Limit the number of containers returned. Default is null, meaning no limit.
     * @param bool     $size  Show size of containers. Default is false, meaning size is not shown.
     * @param array{
     *  ancestor?: string,
     *  before?: string,
     *  expose?: string,
     *  health?: string,
     *  id?: string,
     *  isolation?: string,
     *  label?: string,
     *  name?: string,
     *  network?: string,
     *  publish?: string,
     *  since?: string,
     *  status?: string,
     *  volume?: string
     * } $filters Filters to apply to the query. Default is an empty array.
     *
     * @throws InvalidArgumentException If the limit is less than 1.
     */
    public function __construct(
        private(set) bool $all = false,
        private(set) int|null $limit = null,
        private(set) bool $size = false,
        private(set) array $filters = [],
    ) {
        if ($this->limit !== null && $this->limit < 1) {
            throw new InvalidArgumentException('Limit must be greater than or equal to 1.');
        }
    }

    /**
     * Converts the query to an array format.
     *
     * This method constructs an associative array representing the query parameters,
     * including `all`, `size`, `limit`, and `filters`. The resulting array can be
     * used for further processing or as input for HTTP requests.
     *
     * @return array{
     *  all:bool,
     *  size:bool,
     *  limit?:int|null,
     *  filters?:array{
     *      ancestor?: string,
     *      before?: string,
     *      expose?: string,
     *      health?: string,
     *      id?: string,
     *      isolation?: string,
     *      label?: string,
     *      name?: string,
     *      network?: string,
     *      publish?: string,
     *      since?: string,
     *      status?: string,
     *      volume?: string
     *  }
     * } The query parameters as an associative array.
     */
    public function toArray(): array
    {
        $query = [
            'all' => $this->all,
            'size' => $this->size,
        ];

        if ($this->limit !== null) {
            $query['limit'] = $this->limit;
        }

        if (! empty($this->filters)) {
            $query['filters'] = $this->filters;
        }

        return $query;
    }

    /**
     * Converts the query to a URL-encoded query string.
     *
     * This method converts the query parameters into a URL-encoded string
     * that can be appended to an HTTP request URL. It uses PHP's `http_build_query`
     * function to generate the query string.
     *
     * @return string The URL-encoded query string.
     */
    public function toQueryString(): string
    {
        return http_build_query($this->toArray());
    }
}
