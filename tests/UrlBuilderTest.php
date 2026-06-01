<?php

/**
 * This file is part of contao-community-alliance/url-builder.
 *
 * (c) 2017 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/url-builder
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2014-2017 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/url-builder/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Test;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Main test class for UrlBuilder class.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
#[CoversClass(UrlBuilder::class)]
class UrlBuilderTest extends TestCase
{
    /**
     * Test that everything is empty on a new instance.
     *
     * @return void
     */
    public function testEmpty()
    {
        $test = new UrlBuilder();

        $this->assertEmpty($test->getUser());
        $this->assertEmpty($test->getPass());
        $this->assertEmpty($test->getScheme());
        $this->assertEmpty($test->getHost());
        $this->assertEmpty($test->getPort());
        $this->assertEmpty($test->getPath());
        $this->assertEmpty($test->getFragment());
        $this->assertEmpty($test->getQueryString());
        $this->assertEmpty($test->getUrl());
    }

    /**
     * Test that the parsing of a complete url is successful and the same URL gets regenerated.
     */
    public function testFullUrl(): void
    {
        $url  = 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top';
        $test = new UrlBuilder($url);

        $this->assertSame($url, $test->getUrl());
    }

    /**
     * Prepare URLs for testPartialUrls test.
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function prepareUrls(): array
    {
        $urls = [
            [
                'input'    => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
                'user'     => 'user',
                'pass'     => 'secret',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'port'     => 80,
                'path'     => '/secure/path',
                'fragment' => 'top',
                'query'    => 'auth=1&token=123&perform',
            ],
            [
                'input' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'port'     => 80,
                'path'     => '/secure/path',
                'fragment' => 'top',
                'query'    => 'authenticated=1&token=123&perform',
            ],
            [
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
                'fragment' => 'top',
                'query'    => 'authenticated=1&token=123&perform',
            ],
            [
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
                'query'    => 'authenticated=1&token=123&perform',
            ],
            [
                'input' => '//secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => '//secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'scheme'   => '',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
                'query'    => 'authenticated=1&token=123&perform',
            ],
            [
                'input' => '?authenticated=1&token=123&perform',
                'expected' => '?authenticated=1&token=123&perform',
                'query'    => 'authenticated=1&token=123&perform',
            ],
            [
                'input' => 'authenticated=1&token=123&perform',
                'expected' => 'authenticated=1&token=123&perform',
                'query'    => 'authenticated=1&token=123&perform',
            ],
            [
                'input' => 'http://secure.c-c-a.org/secure/path',
                'expected' => 'http://secure.c-c-a.org/secure/path',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
            ],
            [
                'input' => 'http://secure.c-c-a.org/',
                'expected' => 'http://secure.c-c-a.org/',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/',
            ],
            [
                'input' => 'http://secure.c-c-a.org',
                'expected' => 'http://secure.c-c-a.org',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
            ],
        ];

        return array_map(
            function ($url) {
                return [
                    $url['input'],
                    $url['expected'],
                    $url['user'] ?? null,
                    $url['pass'] ?? null,
                    $url['scheme'] ?? null,
                    $url['host'] ?? null,
                    $url['port'] ?? null,
                    $url['path'] ?? null,
                    $url['fragment'] ?? null,
                    $url['query'] ?? null,
                ];
            },
            $urls
        );
    }

    /**
     * Test partial url parsing and back combining.
     *
     * @param string  $url      The url to test.
     * @param string  $expected The expected result value.
     * @param ?string $user     The user part of the url.
     * @param ?string $pass     The pass part of the url.
     * @param ?string $scheme   The scheme part of the url.
     * @param ?string $host     The host part of the url.
     * @param ?int    $port     The port part of the url.
     * @param ?string $path     The path part of the url.
     * @param ?string $fragment The fragment part of the url.
     * @param ?string $query    The query part of the url.
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[Dataprovider('prepareUrls')]
    public function testPartialUrls(
        string $url,
        string $expected,
        ?string $user,
        ?string $pass,
        ?string $scheme,
        ?string $host,
        ?int $port,
        ?string $path,
        ?string $fragment,
        ?string $query
    ): void {
        $test = UrlBuilder::fromUrl($url);
        $this->assertSame($expected, $test->getUrl(), 'Check failed: expected');
        $this->assertSame($user, $test->getUser(), 'Check failed: user');
        $this->assertSame($pass, $test->getPass(), 'Check failed: pass');
        $this->assertSame($scheme, $test->getScheme(), 'Check failed: scheme');
        $this->assertSame($host, $test->getHost(), 'Check failed: host');
        $this->assertSame($port, $test->getPort(), 'Check failed: port');
        $this->assertSame($path, $test->getPath(), 'Check failed: path');
        $this->assertSame($fragment, $test->getFragment(), 'Check failed: fragment');
        $this->assertSame($query, $test->getQueryString(), 'Check failed: query');
    }

    /**
     * Prepare URLs for testPartialBaseUrls.
     *
     * @return array
     */
    public static function prepareBaseUrls(): array
    {
        $urls = [
            [
                'input' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path',
            ],
            [
                'input' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org:80/secure/path',
            ],
            [
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org/secure/path',
            ],
            [
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'http://secure.c-c-a.org/secure/path',
            ],
            [
                'input' => 'secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'secure.c-c-a.org/secure/path',
            ],
            [
                'input' => '?authenticated=1&token=123&perform',
                'expected' => '',
            ],
            [
                'input' => 'authenticated=1&token=123&perform',
                'expected' => '',
            ],
            [
                'input' => 'http://secure.c-c-a.org/secure/path',
                'expected' => 'http://secure.c-c-a.org/secure/path',
            ],
            [
                'input' => 'http://secure.c-c-a.org/',
                'expected' => 'http://secure.c-c-a.org/',
            ],
            [
                'input' => 'http://secure.c-c-a.org',
                'expected' => 'http://secure.c-c-a.org',
            ],
        ];

        return array_map(
            function ($url) {
                return array($url['input'], $url['expected']);
            },
            $urls
        );
    }

    /**
     * Test partial urls.
     *
     * @param string $url      The url to test.
     * @param string $expected The expected result value.
     */
    #[Dataprovider('prepareBaseUrls')]
    public function testPartialBaseUrls(string $url, string $expected): void
    {
        $test = UrlBuilder::fromUrl($url);
        $this->assertSame($expected, $test->getBaseUrl());
    }

    /**
     * Prepare URLs for testBrokenUrls.
     */
    public static function prepareBrokenUrls(): array
    {
        $urls = [
            [
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path?auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ],
            [
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ],
            [
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ],
            [
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&=&auth=1&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&perform#top',
            ],
            [
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&foo=&auth=1&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?foo&auth=1&perform#top',
            ],
        ];

        return array_map(
            static fn ($url) => [$url['input'], $url['expected']],
            $urls
        );
    }

    /**
     * Test broken urls.
     *
     * @param string $url      The url to test.
     * @param string $expected The expected result value.
     */
    #[Dataprovider('prepareBrokenUrls')]
    public function testBrokenUrls(string $url, string $expected): void
    {
        $test = UrlBuilder::fromUrl($url);
        $this->assertSame($expected, $test->getUrl());
    }

    /**
     * Test that parameters are inserted at the correct position.
     */
    public function testInsertParameter(): void
    {
        $test = new UrlBuilder('http://secure.c-c-a.org');
        $test->insertQueryParameter('test', 'value', 0);
        $this->assertSame('http://secure.c-c-a.org/?test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org');
        $test->insertQueryParameter('test', 'value', 10);
        $this->assertSame('http://secure.c-c-a.org/?test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org/?some=parameter');
        $test->insertQueryParameter('test', 'value', 0);
        $this->assertSame('http://secure.c-c-a.org/?test=value&some=parameter', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org/?some=parameter');
        $test->insertQueryParameter('test', 'value', 1);
        $this->assertSame('http://secure.c-c-a.org/?some=parameter&test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org/?some=parameter');
        $test->insertQueryParameter('test', 'value', 10);
        $this->assertSame('http://secure.c-c-a.org/?some=parameter&test=value', $test->getUrl());
    }

    /**
     * Test that parameters are inserted at the correct position.
     */
    public function testInsertParameterBefore(): void
    {
        $test = new UrlBuilder('http://secure.c-c-a.org');
        $test->insertQueryParameterBefore('test', 'value', 'unknown');
        $this->assertSame('http://secure.c-c-a.org/?test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org/?some=parameter');
        $test->insertQueryParameterBefore('test', 'value', 'some');
        $this->assertSame('http://secure.c-c-a.org/?test=value&some=parameter', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org/?some=parameter');
        $test->insertQueryParameterBefore('test', 'value', 'unknown');
        $this->assertSame('http://secure.c-c-a.org/?some=parameter&test=value', $test->getUrl());
    }

    /**
     * Prepare test data for testQueryParameterCorrectlySeparated().
     */
    public static function prepareQueryParameterTestUrls(): array
    {
        return [
            [
                'expected' => 'http://secure.c-c-a.org/foo?foo=bar',
                'baseUrl'  => 'http://secure.c-c-a.org/foo',
            ],
            [
                'expected' => 'http://secure.c-c-a.org/foo.html?foo=bar',
                'baseUrl'  => 'http://secure.c-c-a.org/foo.html',
            ],
            [
                'expected' => 'http://secure.c-c-a.org/?foo=bar',
                'baseUrl'  => 'http://secure.c-c-a.org',
            ]
        ];
    }

    /**
     * Test that having a query with a bare base url works.
     *
     * @param string $expected The expected result.
     * @param string $baseUrl  The base URL.
     */
    #[Dataprovider('prepareQueryParameterTestUrls')]
    public function testQueryParameterCorrectlySeparated(string $expected, string $baseUrl): void
    {
        $url = new UrlBuilder($baseUrl);
        $url->setQueryParameter('foo', 'bar');

        $this->assertSame($expected, $url->getUrl());
    }

    /**
     * Test that parameters are removed correctly.
     */
    public function testHasQueryParameter(): void
    {
        $test = new UrlBuilder('http://secure.c-c-a.org?test=value');
        $this->assertTrue($test->hasQueryParameter('test'));
    }

    /**
     * Test that parameters are removed correctly.
     */
    public function testUnsetQueryParameter(): void
    {
        $test = new UrlBuilder('http://secure.c-c-a.org?test=value');
        $test->unsetQueryParameter('test');
        $this->assertFalse($test->hasQueryParameter('test'));
        $this->assertSame('http://secure.c-c-a.org', $test->getUrl());
    }

    /**
     * Test that parameters are removed correctly.
     */
    public function testAddQueryParametersFromUrl(): void
    {
        $test = new UrlBuilder('http://secure.c-c-a.org?initial=value&test=nonsense');
        $test->addQueryParametersFromUrl('http://secure.example.org?test=value&foo=bar');
        $this->assertTrue($test->hasQueryParameter('initial'));
        $this->assertTrue($test->hasQueryParameter('test'));
        $this->assertTrue($test->hasQueryParameter('foo'));
        $this->assertSame('value', $test->getQueryParameter('initial'));
        $this->assertSame('value', $test->getQueryParameter('test'));
        $this->assertSame('bar', $test->getQueryParameter('foo'));
        $this->assertSame('http://secure.c-c-a.org/?initial=value&test=value&foo=bar', $test->getUrl());
    }

    /**
     * Test that creating an URL from scratch works.
     */
    public function testCreationFromEmpty(): void
    {
        $test = new UrlBuilder();

        $test
            ->setScheme('spdy')
            ->setUser('test')
            ->setPass('secret')
            ->setHost('example.org')
            ->setPort(50)
            ->setPath('directory/file')
            ->setQueryParameter('test', 'value');

        $this->assertSame('spdy://test:secret@example.org:50/directory/file?test=value', $test->getUrl());
    }
}
