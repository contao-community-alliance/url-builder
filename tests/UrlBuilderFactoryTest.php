<?php

/**
 * This file is part of contao-community-alliance/url-builder.
 *
 * (c) 2018 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/url-builder
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2014-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/url-builder/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Test;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilderFactory;
use ContaoCommunityAlliance\UrlBuilder\Test\TestCase;

/**
 * Main test class for UrlBuilderFactory class.
 */
class UrlBuilderFactoryTest extends TestCase
{
    /**
     * Test that the request token get's appended.
     *
     * @return void
     */
    public function testCreateBuilder()
    {
        $url      = 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top';
        $expected = 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top';
        $factory  = new UrlBuilderFactory();

        $test = $factory->create($url);

        $this->assertInstanceOf(UrlBuilder::class, $test);
        $this->assertSame($expected, $test->getUrl());
    }
}
