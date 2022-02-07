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

/**
 * Main test class for UrlBuilder class.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
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
     *
     * @return void
     */
    public function testFullUrl()
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
    public function prepareUrls()
    {
        $urls = array(
            array(
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
            ),
            array(
                'input' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'port'     => 80,
                'path'     => '/secure/path',
                'fragment' => 'top',
                'query'    => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
                'fragment' => 'top',
                'query'    => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
                'query'    => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => '//secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => '//secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'scheme'   => '',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
                'query'    => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => '?authenticated=1&token=123&perform',
                'expected' => '?authenticated=1&token=123&perform',
                'query'    => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => 'authenticated=1&token=123&perform',
                'expected' => 'authenticated=1&token=123&perform',
                'query'    => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path',
                'expected' => 'http://secure.c-c-a.org/secure/path',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/secure/path',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/',
                'expected' => 'http://secure.c-c-a.org/',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
                'path'     => '/',
            ),
            array(
                'input' => 'http://secure.c-c-a.org',
                'expected' => 'http://secure.c-c-a.org',
                'scheme'   => 'http',
                'host'     => 'secure.c-c-a.org',
            ),
        );

        return array_map(
            function ($url) {
                return array(
                    $url['input'],
                    $url['expected'],
                    isset($url['user'])     ? $url['user']     : null,
                    isset($url['pass'])     ? $url['pass']     : null,
                    isset($url['scheme'])   ? $url['scheme']   : null,
                    isset($url['host'])     ? $url['host']     : null,
                    isset($url['port'])     ? $url['port']     : null,
                    isset($url['path'])     ? $url['path']     : null,
                    isset($url['fragment']) ? $url['fragment'] : null,
                    isset($url['query'])    ? $url['query']    : null,
                );
            },
            $urls
        );
    }

    /**
     * Test partial url parsing and back combining.
     *
     * @param string $url      The url to test.
     *
     * @param string $expected The expected result value.
     *
     * @param string $user     The user part of the url.
     *
     * @param string $pass     The pass part of the url.
     *
     * @param string $scheme   The scheme part of the url.
     *
     * @param string $host     The host part of the url.
     *
     * @param string $port     The port part of the url.
     *
     * @param string $path     The path part of the url.
     *
     * @param string $fragment The fragment part of the url.
     *
     * @param string $query    The query part of the url.
     *
     * @return void
     *
     * @dataProvider prepareUrls
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function testPartialUrls($url, $expected, $user, $pass, $scheme, $host, $port, $path, $fragment, $query)
    {
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
    public function prepareBaseUrls()
    {
        $urls = array(
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path',
            ),
            array(
                'input' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org:80/secure/path',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org/secure/path',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'http://secure.c-c-a.org/secure/path',
            ),
            array(
                'input' => 'secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'secure.c-c-a.org/secure/path',
            ),
            array(
                'input' => '?authenticated=1&token=123&perform',
                'expected' => '',
            ),
            array(
                'input' => 'authenticated=1&token=123&perform',
                'expected' => '',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path',
                'expected' => 'http://secure.c-c-a.org/secure/path',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/',
                'expected' => 'http://secure.c-c-a.org/',
            ),
            array(
                'input' => 'http://secure.c-c-a.org',
                'expected' => 'http://secure.c-c-a.org',
            ),
        );

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
     *
     * @param string $expected The expected result value.
     *
     * @return void
     *
     * @dataProvider prepareBaseUrls
     */
    public function testPartialBaseUrls($url, $expected)
    {
        $test = UrlBuilder::fromUrl($url);
        $this->assertSame($expected, $test->getBaseUrl());
    }

    /**
     * Prepare URLs for testBrokenUrls.
     *
     * @return array
     */
    public function prepareBrokenUrls()
    {
        $urls = array(
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path?auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&=&auth=1&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&foo=&auth=1&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?foo&auth=1&perform#top',
            ),
        );

        return array_map(
            function ($url) {
                return array($url['input'], $url['expected']);
            },
            $urls
        );
    }

    /**
     * Test broken urls.
     *
     * @param string $url      The url to test.
     *
     * @param string $expected The expected result value.
     *
     * @return void
     *
     * @dataProvider prepareBrokenUrls
     */
    public function testBrokenUrls($url, $expected)
    {
        $test = UrlBuilder::fromUrl($url);
        $this->assertSame($expected, $test->getUrl());
    }

    /**
     * Test that parameters are inserted at the correct position.
     *
     * @return void
     */
    public function testInsertParameter()
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
     *
     * @return void
     */
    public function testInsertParameterBefore()
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
     *
     * @return array
     */
    public function prepareQueryParameterTestUrls()
    {
        return array(
            array(
                'expected' => 'http://secure.c-c-a.org/foo?foo=bar',
                'baseUrl'  => 'http://secure.c-c-a.org/foo',
            ),
            array(
                'expected' => 'http://secure.c-c-a.org/foo.html?foo=bar',
                'baseUrl'  => 'http://secure.c-c-a.org/foo.html',
            ),
            array(
                'expected' => 'http://secure.c-c-a.org/?foo=bar',
                'baseUrl'  => 'http://secure.c-c-a.org',
            )
        );
    }

    /**
     * Test that having a query with a bare base url works.
     *
     * @param string $expected The expected result.
     * @param string $baseUrl  The base URL.
     *
     * @dataProvider prepareQueryParameterTestUrls
     */
    public function testQueryParameterCorrectlySeparated($expected, $baseUrl)
    {
        $url = new UrlBuilder($baseUrl);
        $url->setQueryParameter('foo', 'bar');

        $this->assertSame($expected, $url->getUrl());
    }

    /**
     * Test that parameters are removed correctly.
     *
     * @return void
     */
    public function testHasQueryParameter()
    {
        $test = new UrlBuilder('http://secure.c-c-a.org?test=value');
        $this->assertTrue($test->hasQueryParameter('test'));
    }

    /**
     * Test that parameters are removed correctly.
     *
     * @return void
     */
    public function testUnsetQueryParameter()
    {
        $test = new UrlBuilder('http://secure.c-c-a.org?test=value');
        $test->unsetQueryParameter('test');
        $this->assertFalse($test->hasQueryParameter('test'));
        $this->assertSame('http://secure.c-c-a.org', $test->getUrl());
    }

    /**
     * Test that parameters are removed correctly.
     *
     * @return void
     */
    public function testAddQueryParametersFromUrl()
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
     *
     * @return void
     */
    public function testCreationFromEmpty()
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
