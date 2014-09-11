<?php
/**
 * The Contao Community Alliance url-builder library allows easy generating and manipulation of urls.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\UrlBuilder\Test
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Test;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;

/**
 * Main test class for UrlBuilder class.
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
     */
    public function prepareUrls()
    {
        $urls = array(
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'http://secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
            ),
            array(
                'input' => 'secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
                'expected' => 'secure.c-c-a.org/secure/path?authenticated=1&token=123&perform',
            ),
            array(
                'input' => '?authenticated=1&token=123&perform',
                'expected' => 'authenticated=1&token=123&perform',
            ),
            array(
                'input' => 'authenticated=1&token=123&perform',
                'expected' => 'authenticated=1&token=123&perform',
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
     * Test partial url parsing and back combining.
     *
     * @param string $url      The url to test.
     *
     * @param string $expected The expected result value.
     *
     * @return void
     *
     * @dataProvider prepareUrls
     */
    public function testPartialUrls($url, $expected)
    {
        $test = new UrlBuilder($url);
        $this->assertSame($expected, $test->getUrl());
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
        $test = new UrlBuilder($url);
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
        $test = new UrlBuilder($url);
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
        $this->assertSame('http://secure.c-c-a.org?test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org');
        $test->insertQueryParameter('test', 'value', 10);
        $this->assertSame('http://secure.c-c-a.org?test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org?some=parameter');
        $test->insertQueryParameter('test', 'value', 0);
        $this->assertSame('http://secure.c-c-a.org?test=value&some=parameter', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org?some=parameter');
        $test->insertQueryParameter('test', 'value', 1);
        $this->assertSame('http://secure.c-c-a.org?some=parameter&test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org?some=parameter');
        $test->insertQueryParameter('test', 'value', 10);
        $this->assertSame('http://secure.c-c-a.org?some=parameter&test=value', $test->getUrl());
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
        $this->assertSame('http://secure.c-c-a.org?test=value', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org?some=parameter');
        $test->insertQueryParameterBefore('test', 'value', 'some');
        $this->assertSame('http://secure.c-c-a.org?test=value&some=parameter', $test->getUrl());

        $test = new UrlBuilder('http://secure.c-c-a.org?some=parameter');
        $test->insertQueryParameterBefore('test', 'value', 'unknown');
        $this->assertSame('http://secure.c-c-a.org?some=parameter&test=value', $test->getUrl());
    }
}
