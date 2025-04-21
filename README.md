[![Latest Stable Version](http://poser.pugx.org/old-home/docker-client/v)](https://packagist.org/packages/old-home/docker-client)
[![PHP Version Require](http://poser.pugx.org/old-home/docker-client/require/php)](https://packagist.org/packages/old-home/docker-client)
[![codecov](https://codecov.io/gh/old-home/docker-client/graph/badge.svg?token=4G0FL9FPUI)](https://codecov.io/gh/old-home/docker-client)

# Docker Client for PHP

A PHP client library for Docker API

## Overview

This library provides a client for easily interacting with the Docker API from PHP. It offers interfaces for managing Docker resources such as containers, images, networks, and volumes.

## Requirements

- PHP 8.4 or higher
- ext-curl
- Docker engine API 1.48 or higher

## Installation

Install using Composer:

```bash
composer require graywings/docker-client
```

## Usage

```php
use Graywings\DockerClient\DockerClient;

$client = new DockerClient();
$containers = $client->getContainers()
```

## Development

### Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`

### Testing

```bash
# Run all tests with coverage report
composer test:all

# Run tests without coverage report
composer test:no-report
```

### Code Quality

```bash
# Run all linters
composer lint

# Individual linters
composer lint:phpstan  # Static analysis with PHPStan
composer lint:phpcs    # Code style check with PHP_CodeSniffer
composer lint:phpcbf   # Code style fix with PHP_CodeSniffer
composer lint:psalm    # Static analysis with Psalm
```

## License

MIT

## Author

- Taira Terashima (taira.terashima@gmail.com)
