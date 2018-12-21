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
 * @copyright  2014-2017 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/url-builder/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Test\Contao;

use ContaoCommunityAlliance\UrlBuilder\Contao\CsrfUrlBuilder;
use ContaoCommunityAlliance\UrlBuilder\Test\TestCase;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Main test class for CsrfUrlBuilder class.
 */
class CsrfUrlBuilderTest extends TestCase
{
    /**
     * Test that the request token get's appended.
     *
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testAppendedRequestToken()
    {
        $tokenManager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMockForAbstractClass();
        $tokenManager
            ->expects($this->once())
            ->method('getToken')
            ->with('tokenName')
            ->willReturn(new CsrfToken('tokenName', 'token-value'));

        $url      = 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform#top';
        $expected = 'http://user:secret@secure.c-c-a.org:80/secure/path?auth=1&token=123&perform&rt=token-value#top';
        $test     = new CsrfUrlBuilder($tokenManager, 'tokenName', $url);

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
        $tokenManager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMockForAbstractClass();
        $tokenManager
            ->expects($this->once())
            ->method('getToken')
            ->with('tokenName')
            ->willReturn(new CsrfToken('tokenName', 'token-value'));

        $url      = 'http://user:secret@secure.c-c-a.org:80/secure/path#top';
        $expected = 'http://user:secret@secure.c-c-a.org:80/secure/path?rt=token-value#top';
        $test     = new CsrfUrlBuilder($tokenManager, 'tokenName', $url);

        $this->assertSame($expected, $test->getUrl());
    }
}
