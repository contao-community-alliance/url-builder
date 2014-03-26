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

class BackendUrlBuilderTest
	extends TestCase
{
	public function testAppendedRequestToken()
	{
		define('REQUEST_TOKEN', 'requestToken');

		$url      = 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform#top';
		$expected = 'http://user:secret@secure.c-c-a.org:80/secure/path?authenticated=1&token=123&perform&rt=requestToken#top';
		$test     = new BackendUrlBuilder($url);

		$this->assertSame($expected, $test->getUrl());
	}
}
