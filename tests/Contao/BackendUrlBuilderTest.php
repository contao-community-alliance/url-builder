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
     */
    public function testThrowsExceptionWithoutConstant()
    {
        $test = new BackendUrlBuilder('http://secure.c-c-a.org');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Request token not defined');

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
