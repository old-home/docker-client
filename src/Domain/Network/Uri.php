<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

use InvalidArgumentException;
use Override;
use Psr\Http\Message\UriInterface;

use function array_key_exists;
use function implode;
use function preg_match;
use function str_contains;

/**
 * This class represents a URL and implements the `UriInterface` from PSR-7.
 * It provides methods for parsing, modifying, and retrieving components of a URL,
 * such as the scheme, host, port, path, query, fragment, and user information.
 *
 * The class ensures immutability by returning new instances when modifications
 * are made to the URL components.
 */
final readonly class Uri implements UriInterface
{
    /**
     * @param string   $scheme   The scheme of the URL (e.g., "http", "https").
     * @param string   $host     The host of the URL (e.g., "example.com").
     * @param int|null $port     The port of the URL (e.g., 80, 443).
     * @param string   $path     The path of the URL (e.g., "/path/to/resource").
     * @param string   $query    The query string of the URL (e.g., "key=value").
     * @param string   $fragment The fragment of the URL (e.g., "#section").
     * @param string   $user     The user's name.
     * @param string   $pass     The user password.
     */
    public function __construct(
        private(set) string $scheme,
        private(set) string $host,
        private(set) int|null $port,
        private(set) string $path,
        private(set) string $query,
        private(set) string $fragment,
        private(set) string $user,
        private(set) string $pass,
        private(set) bool $hasAuthorityPart = true,
    ) {
    }

    /**
     * Parses a URL string and creates a `Url` object.
     *
     * @param string $uri The URL string to parse.
     *
     * @return self A new `Url` object representing the parsed URL.
     *
     * @throws InvalidArgumentException If the URL is invalid or missing required components.
     */
    public static function parse(string $uri): self
    {
        if (str_contains($uri, '://')) {
            return self::parseHavingAuthority($uri);
        }

        return self::parseNotHavingAuthority($uri);
    }

    /**
     * Parse URI string having authority
     */
    private static function parseHavingAuthority(string $uri): self
    {
        $regExp = implode('', [
            '/^',
            '((?<scheme>[^:\/?#]+):)',
            '\/\/',
            '((?<user>[^:\/?#]+)\:*(?<pass>[^:\/?#]+)?@)?',
            '(?<domain>[^:\/?#]+)?',
            '(:(?<port>[\d]+)+)?',
            '(?<pathString>[^?#]+)?',
            '(\?(?<query>[^?#]+))?',
            '(#(?<fragment>[^?#]+))?',
            '/',
        ]);

        /**
         * @var array{
         *     scheme?:string,
         *     user?:string,
         *     pass?:string,
         *     domain?:string,
         *     port?:string,
         *     pathString?:string,
         *     query?:string,
         *     fragment?:string
         * } $matches
         */
        $matches = [];
        preg_match($regExp, $uri, $matches);

        if (! array_key_exists('scheme', $matches)) {
            throw new InvalidArgumentException('URI scheme missing: ' . $uri);
        }

        return new self(
            $matches['scheme'],
            $matches['domain'] ?? '',
            array_key_exists('port', $matches)
            && $matches['port'] !== ''
                ? (int) $matches['port']
                : null,
            $matches['pathString'] ?? '',
            $matches['query'] ?? '',
            $matches['fragment'] ?? '',
            $matches['user'] ?? '',
            $matches['pass'] ?? '',
        );
    }

    /**
     * Parse URI string not having authority
     */
    private static function parseNotHavingAuthority(string $uri): self
    {
        $regExp = implode('', [
            '/^',
            '((?<scheme>[^:\/?#]+):)',
            '(?<pathString>[^?#]+)?',
            '(\?(?<query>[^?#]+))?',
            '(#(?<fragment>[^?#]+))?',
            '/',
        ]);
        /**
         * @var array{
         *     scheme:string,
         *     pathString:string,
         *     query:string,
         *     fragment:string
         * } $matches
         */
        $matches = [];
        preg_match($regExp, $uri, $matches);
        if (! array_key_exists('scheme', $matches)) {
            throw new InvalidArgumentException('URI scheme missing: ' . $uri);
        }

        return new self(
            $matches['scheme'],
            '',
            null,
            $matches['pathString'] ?? '',
            $matches['query'] ?? '',
            $matches['fragment'] ?? '',
            '',
            '',
            false,
        );
    }

    /**
     * Compares the current URL with another URL.
     *
     * @param self $other The URL to compare with.
     *
     * @return bool True if the URLs are equal, false otherwise.
     */
    public function equals(self $other): bool
    {
        return $this->scheme === $other->scheme
            && $this->host === $other->host
            && $this->port === $other->port
            && $this->path === $other->path
            && $this->query === $other->query
            && $this->fragment === $other->fragment
            && $this->user === $other->user
            && $this->pass === $other->pass;
    }

    /**
     * Retrieves the scheme of the URL.
     *
     * @return string The scheme of the URL.
     */
    #[Override]
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Retrieves the authority component of the URL.
     *
     * @return string The authority component of the URL.
     */
    #[Override]
    public function getAuthority(): string
    {
        $authority = $this->host;
        if (! empty($this->user)) {
            $authority = $this->user . ':' . $this->pass . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * Retrieves the user information of the URL.
     *
     * @return string The user information of the URL.
     */
    #[Override]
    public function getUserInfo(): string
    {
        return $this->user . ':' . $this->pass;
    }

    /**
     * Retrieves the host of the URL.
     *
     * @return string The host of the URL.
     */
    #[Override]
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Retrieves the port of the URL.
     *
     * @return int|null The port of the URL, or null if not specified.
     */
    #[Override]
    public function getPort(): int|null
    {
        return $this->port;
    }

    /**
     * Retrieves the path of the URL.
     *
     * @return string The path of the URL.
     */
    #[Override]
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retrieves the query string of the URL.
     *
     * @return string The query string of the URL.
     */
    #[Override]
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Retrieves the fragment of the URL.
     *
     * @return string The fragment of the URL.
     */
    #[Override]
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Modifies the scheme of the URL.
     *
     * @param string $scheme The new scheme.
     *
     * @return Uri A new `Url` object with the modified scheme.
     */
    #[Override]
    public function withScheme(string $scheme): Uri
    {
        return new self(
            $scheme,
            $this->host,
            $this->port,
            $this->path,
            $this->query,
            $this->fragment,
            $this->user,
            $this->pass,
        );
    }

    /**
     * Modifies the user information of the URL.
     *
     * This method updates the user information (username and optional password)
     * and returns a new `Url` object with the modified user information.
     *
     * @param string      $user     The username.
     * @param string|null $password The password (optional).
     *
     * @return Uri A new `Url` object with the modified user information.
     */
    #[Override]
    public function withUserInfo(string $user, string|null $password = null): Uri
    {
        return new self(
            $this->scheme,
            $this->host,
            $this->port,
            $this->path,
            $this->query,
            $this->fragment,
            $user,
            $password ?? '',
        );
    }

    /**
     * Modifies the host of the URL.
     *
     * @param string $host The new host.
     *
     * @return Uri A new `Url` object with the modified host.
     */
    #[Override]
    public function withHost(string $host): Uri
    {
        return new self(
            $this->scheme,
            $host,
            $this->port,
            $this->path,
            $this->query,
            $this->fragment,
            $this->user,
            $this->pass,
        );
    }

    /**
     * Modifies the port of the URL.
     *
     * @param int|null $port The new port (or null to remove the port).
     *
     * @return Uri A new `Url` object with the modified port.
     */
    #[Override]
    public function withPort(int|null $port): Uri
    {
        return new self(
            $this->scheme,
            $this->host,
            $port,
            $this->path,
            $this->query,
            $this->fragment,
            $this->user,
            $this->pass,
        );
    }

    /**
     * Modifies the path of the URL.
     *
     * @param string $path The new path.
     *
     * @return Uri A new `Url` object with the modified path.
     */
    #[Override]
    public function withPath(string $path): Uri
    {
        return new self(
            $this->scheme,
            $this->host,
            $this->port,
            $path,
            $this->query,
            $this->fragment,
            $this->user,
            $this->pass,
        );
    }

    /**
     * Modifies the query string of the URL.
     *
     * @param string $query The new query string.
     *
     * @return Uri A new `Url` object with the modified query string.
     */
    #[Override]
    public function withQuery(string $query): Uri
    {
        return new self(
            $this->scheme,
            $this->host,
            $this->port,
            $this->path,
            $query,
            $this->fragment,
            $this->user,
            $this->pass,
        );
    }

    /**
     * Modifies the fragment of the URL.
     *
     * @param string $fragment The new fragment.
     *
     * @return Uri A new `Url` object with the modified fragment.
     */
    #[Override]
    public function withFragment(string $fragment): Uri
    {
        return new self(
            $this->scheme,
            $this->host,
            $this->port,
            $this->path,
            $this->query,
            $fragment,
            $this->user,
            $this->pass,
        );
    }

    /**
     * Converts the URL object to its string representation.
     *
     * This method constructs the full URL string by combining its components,
     * such as the scheme, authority, path, query, and fragment. It ensures that
     * the resulting string is a valid URL format.
     *
     * @return string The string representation of the URL.
     */
    #[Override]
    public function __toString(): string
    {
        $uri = '';
        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        if ($this->hasAuthorityPart) {
            $uri .= '//' . $this->getAuthority();
        }

        if ($this->path !== '') {
            $uri .= $this->path;
        }

        if ($this->query !== '') {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }
}
