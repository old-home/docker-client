<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Tests\Unit\Domain\Network;

use Graywings\DockerClient\Domain\Network\Uri;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

/**
 * This class contains unit tests for the Url class, which represents
 * a URL and provides methods for parsing, validating, and manipulating
 * various components of a URL, such as scheme, host, port, path, query,
 * and fragment.
 */
#[CoversClass(Uri::class)]
final class UriTest extends TestCase
{
    /**
     * Tests parsing a valid URL string.
     *
     * This test ensures that a valid URL string can be parsed into a Url object
     * and that all components (scheme, host, port, path, query, fragment) are
     * correctly extracted and accessible.
     */
    public function testParseValidUrl(): void
    {
        $url = Uri::parse('https://example.com:8080/path?query=string#fragment');

        $this->assertInstanceOf(Uri::class, $url);
        $this->assertInstanceOf(UriInterface::class, $url);
        $this->assertSame('https', $url->scheme);
        $this->assertSame('example.com', $url->host);
        $this->assertSame(8080, $url->port);
        $this->assertSame('/path', $url->path);
        $this->assertSame('query=string', $url->query);
        $this->assertSame('fragment', $url->fragment);
        $this->assertSame('example.com:8080', $url->getAuthority());
        $this->assertSame('https://example.com:8080/path?query=string#fragment', (string) $url);
    }

    /**
     * Tests parsing a URL with user information.
     *
     * This test ensures that a URL containing user information (username and
     * password) can be parsed correctly and that the user info and authority
     * components are properly extracted.
     */
    public function testParseUrlWithUserInfo(): void
    {
        $url = Uri::parse('https://user:password@example.com/path');

        $this->assertSame('user:password', $url->user . ':' . $url->pass);
        $this->assertSame('user:password@example.com', $url->getAuthority());
    }

    /**
     * Tests that parsing an invalid URL throws an exception.
     *
     * This test ensures that attempting to parse an invalid URL string results
     * in an InvalidArgumentException with the expected error message.
     */
    public function testParseInvalidUrlThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URI scheme missing: invalid-url');

        Uri::parse('invalid-url');
    }

    /**
     * Tests that parsing a URL without a scheme throws an exception.
     *
     * This test ensures that a URL missing the scheme component results in
     * an InvalidArgumentException with the expected error message.
     */
    public function testParseUrlWithoutSchemeThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URI scheme missing: //example.com');

        Uri::parse('//example.com');
    }

    /**
     * Tests that parsing a URL with missing scheme but containing authority throws an exception.
     *
     * This test ensures that attempting to parse a URL with an empty scheme but containing
     * authority (://example.com) results in an InvalidArgumentException with the expected
     * error message.
     */
    public function testParseUrlWithoutSchemeWithContainsAuthority(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URI scheme missing: ://example.com');
        $url = Uri::parse('://example.com');
        $this->assertSame('example.com', $url->host);
    }

    /**
     * Tests creating a Url object with specific values for all components.
     *
     * This test ensures that a Url object can be instantiated with specific
     * values for scheme, host, port, path, query, fragment, and user info,
     * and that these values are correctly stored and accessible.
     */
    public function testCreateUrlWithValues(): void
    {
        $url = new Uri(
            scheme: 'https',
            host: 'example.com',
            port: 8080,
            path: '/path',
            query: 'query=string',
            fragment: 'fragment',
            user: 'user',
            pass: 'password',
        );

        $this->assertSame('https', $url->scheme);
        $this->assertSame('example.com', $url->host);
        $this->assertSame(8080, $url->port);
        $this->assertSame('/path', $url->path);
        $this->assertSame('query=string', $url->query);
        $this->assertSame('fragment', $url->fragment);
        $this->assertSame('user:password', $url->getUserInfo());
    }

    /**
     * Tests modifying the scheme of a Url object.
     *
     * This test ensures that the `withScheme` method correctly updates the
     * scheme of a Url object and returns a new instance with the updated value.
     */
    public function testWithScheme(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withScheme('http');

        $this->assertSame('http', $newUrl->getScheme());
        $this->assertSame('http://example.com/path', (string) $newUrl);
    }

    /**
     * Tests modifying the user information of a Url object.
     *
     * This test ensures that the `withUserInfo` method correctly updates the
     * user info of a Url object and returns a new instance with the updated value.
     */
    public function testWithUserInfo(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withUserInfo('user', 'password');

        $this->assertSame('user:password', $newUrl->getUserInfo());
        $this->assertSame('https://user:password@example.com/path', (string) $newUrl);
    }

    /**
     * Tests modifying the host of a Url object.
     *
     * This test ensures that the `withHost` method correctly updates the
     * host of a Url object and returns a new instance with the updated value.
     */
    public function testWithHost(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withHost('newexample.com');

        $this->assertSame('newexample.com', $newUrl->getHost());
        $this->assertSame('https://newexample.com/path', (string) $newUrl);
    }

    /**
     * Tests modifying the port of a Url object.
     *
     * This test ensures that the `withPort` method correctly updates the
     * port of a Url object and returns a new instance with the updated value.
     */
    public function testWithPort(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withPort(8080);

        $this->assertSame(8080, $newUrl->getPort());
        $this->assertSame('https://example.com:8080/path', (string) $newUrl);
    }

    /**
     * Tests modifying the path of a Url object.
     *
     * This test ensures that the `withPath` method correctly updates the
     * path of a Url object and returns a new instance with the updated value.
     */
    public function testWithPath(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withPath('/newpath');

        $this->assertSame('/newpath', $newUrl->getPath());
        $this->assertSame('https://example.com/newpath', (string) $newUrl);
    }

    /**
     * Tests modifying the query of a Url object.
     *
     * This test ensures that the `withQuery` method correctly updates the
     * query of a Url object and returns a new instance with the updated value.
     */
    public function testWithQuery(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withQuery('newquery');

        $this->assertSame('newquery', $newUrl->getQuery());
        $this->assertSame('https://example.com/path?newquery', (string) $newUrl);
    }

    /**
     * Tests modifying the fragment of a Url object.
     *
     * This test ensures that the `withFragment` method correctly updates the
     * fragment of a Url object and returns a new instance with the updated value.
     */
    public function testWithFragment(): void
    {
        $url    = Uri::parse('https://example.com/path');
        $newUrl = $url->withFragment('newfragment');

        $this->assertSame('newfragment', $newUrl->getFragment());
        $this->assertSame('https://example.com/path#newfragment', (string) $newUrl);
    }

    /**
     * Tests the uri `unix://` started.
     */
    public function testUnixSchemaUrl(): void
    {
        $url = Uri::parse('unix:///var/run/docker.sock');
        $this->assertSame('unix:///var/run/docker.sock', (string) $url);
        $this->assertSame('unix', $url->scheme);
    }

    /**
     * Tests parsing a URN schema URL string.
     *
     * Verifies that a URN schema URL string can be parsed correctly, ensuring
     * the scheme is accurately extracted and the string representation of the
     * URL remains intact.
     */
    public function testUrnSchemaUrl(): void
    {
        $url = Uri::parse('urn:example:animal:ferret:nose');
        $this->assertSame('urn:example:animal:ferret:nose', (string) $url);
        $this->assertSame('urn', $url->scheme);
    }

    /**
     * Tests the `equals` method of the Url class.
     *
     * This test ensures that the `equals` method correctly determines whether
     * two Url objects are equal based on their components.
     */
    public function testEquals(): void
    {
        $url1 = Uri::parse('https://example.com:8080/path?query=string#fragment');
        $url2 = Uri::parse('https://example.com:8080/path?query=string#fragment');
        $url3 = Uri::parse('https://example.com:8080/path?query=different#fragment');
        $url4 = Uri::parse('http://example.com:8080/path?query=string#fragment');

        // Test equality for identical URLs
        $this->assertTrue($url1->equals($url2));

        // Test inequality for different query strings
        $this->assertFalse($url1->equals($url3));

        // Test inequality for different schemes
        $this->assertFalse($url1->equals($url4));

        // Test inequality for completely different URLs
        $url5 = Uri::parse('https://different.com/path');
        $this->assertFalse($url1->equals($url5));
    }
}
