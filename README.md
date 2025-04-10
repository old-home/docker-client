# Docker Client for PHP

A PHP client library for Docker API

## Overview

This library provides a client for easily interacting with the Docker API from PHP. It offers interfaces for managing Docker resources such as containers, images, networks, and volumes.

## Requirements

- PHP 8.4 or higher
- ext-bcmath
- ext-curl
- ext-gmp

## Installation

Install using Composer:

```bash
composer require graywings/docker-client
```

## Usage

```php
use Graywings\DockerClient\DockerClient;

$client = new DockerClient();
$containers = $client->containers()->list();
```

For detailed usage instructions, please refer to the [Project Guide](doc/PROJECT_GUID.md).

## Development

### Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`

### Testing

```bash
# Run all tests with coverage report
composer test

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
```

## License

MIT

## Author

- Taira Terashima (taira.terashima@gmail.com)
