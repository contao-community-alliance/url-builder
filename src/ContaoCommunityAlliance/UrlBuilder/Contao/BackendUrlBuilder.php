<?php

/**
 * This file is part of contao-community-alliance/url-builder.
 *
 * (c) 2016 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/dc-general-contao-frontend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2014-2016 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/url-builder/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Contao;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;

/**
 * URL builder for the Contao Backend.
 *
 * @package ContaoCommunityAlliance\UrlBuilder\Contao
 */
class BackendUrlBuilder extends UrlBuilder
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
        if ($query) {
            $query .= '&';
        }

        if (!defined('REQUEST_TOKEN')) {
            throw new \RuntimeException('Request token not defined - can not append to query string.');
        }

        $query .= 'rt=' . REQUEST_TOKEN;

        return $query;
    }
}
