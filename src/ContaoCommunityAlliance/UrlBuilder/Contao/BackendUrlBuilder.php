<?php
/**
 * The Contao Community Alliance url-builder library allows easy generating and manipulation of urls.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\UrlBuilder
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Contao;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;

/**
 * URL builder for the Contao Backend.
 *
 * @package ContaoCommunityAlliance\UrlBuilder\Contao
 */
class BackendUrlBuilder
	extends UrlBuilder
{
	/**
	 * Retrieve the serialized query string.
	 *
	 * @return string
	 *
	 * @throws \RuntimeException If no REQUEST_TOKEN constant exists.
	 */
	public function getQueryString()
	{
		$query = parent::getQueryString();
		if ($query)
		{
			$query .= '&';
		}

		if (!defined('REQUEST_TOKEN'))
		{
			throw new \RuntimeException('Request token not defined - can not append to query string.');
		}

		$query .= 'rt=' . REQUEST_TOKEN;

		return $query;
	}
}
