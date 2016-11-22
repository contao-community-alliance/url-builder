<?php
/**
 * The Contao Community Alliance url-builder library allows easy generating and manipulation of urls.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\UrlBuilder\Test
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Test\Contao;

use ContaoCommunityAlliance\UrlBuilder\Contao\BackendUrlBuilder;
use ContaoCommunityAlliance\UrlBuilder\Test\TestCase;

/**
 * Main test class for BackendUrlBuilder class.
 */
class BackendUrlBuilderTest extends TestCase
{
    /**
     * Test that an exception is thrown when the constant is not defined.
     *
     * @return void
     *
     * @runInSeparateProcess
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Request token not defined
     */
    public function testThrowsExceptionWithoutConstant()
    {
        $test = new BackendUrlBuilder('http://secure.c-c-a.org');
        $test->getUrl();
    }

    /**
     * Test that the request token get's appended.
     *
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testAppendedRequestToken()
    {
        define('REQUEST_TOKEN', 'requestToken');

        $url      = 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top';
        $expected = 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform&rt=requestToken#top';
        $test     = new BackendUrlBuilder($url);

        $this->assertSame($expected, $test->getUrl());
    }

    /**
     * Test that the request token get's appended.
     *
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testAppendedRequestTokenAsOnlyParameter()
    {
        define('REQUEST_TOKEN', 'requestToken');

        $url      = 'http://user:secret@secure.c-c-a.org:80/secure/path#top';
        $expected = 'http://user:secret@secure.c-c-a.org:80/secure/path?rt=requestToken#top';
        $test     = new BackendUrlBuilder($url);

        $this->assertSame($expected, $test->getUrl());
    }
}
