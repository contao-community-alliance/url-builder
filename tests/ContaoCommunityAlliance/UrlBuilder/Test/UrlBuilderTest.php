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

class UrlBuilderTest extends TestCase
{
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

    public function testFullUrl()
    {
        $url = 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top';
        $test = new UrlBuilder($url);

        $this->assertSame($url, $test->getUrl());
    }

    public function prepareUrls()
    {
        $urls = array(
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
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
     * @param string $url The url to test.
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
     * Test broken urls.
     *
     * @param string $url The url to test.
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

    public function prepareBrokenUrls()
    {
        $urls = array(
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path?authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&=&authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top',
            ),
            array(
                'input' => 'http://user:secret@secure.c-c-a.org:80/////secure////path??=&foo=&authenticated=1&token=123&perform#top',
                'expected' => 'http://user:secret@secure.c-c-a.org:80/secure/path?foo&authenticated=1&token=123&perform#top',
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
     * @param string $url The url to test.
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
}
