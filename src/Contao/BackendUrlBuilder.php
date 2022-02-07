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

namespace ContaoCommunityAlliance\UrlBuilder\Contao;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use RuntimeException;

/**
 * URL builder for the Contao Backend.
 *
 * @package ContaoCommunityAlliance\UrlBuilder\Contao
 *
 * @deprecated The backend url builder is deprecated since 1.3 and removed in 2.0.
 *             Use instead csrf url builder.
 */
class BackendUrlBuilder extends UrlBuilder
{
    /**
     * Retrieve the serialized query string.
     *
     * @return string
     *
     * @throws RuntimeException If no REQUEST_TOKEN constant exists.
     */
    public function getQueryString()
    {
        $query = (string) parent::getQueryString();
        if (!empty($query)) {
            $query .= '&';
        }

        if (!defined('REQUEST_TOKEN')) {
            throw new RuntimeException('Request token not defined - can not append to query string.');
        }
        $token = (string) REQUEST_TOKEN;

        $query .= 'rt=' . $token;

        return $query;
    }
}
