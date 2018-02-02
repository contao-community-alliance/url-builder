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

namespace ContaoCommunityAlliance\UrlBuilder;

/**
 * General purpose URL builder factory.
 *
 * @package ContaoCommunityAlliance\UrlBuilder
 */
class UrlBuilderFactory implements UrlBuilderFactoryInterface
{
    /**
     * Retrieve an instance.
     *
     * @param string $url The initial URL.
     *
     * @return UrlBuilder
     */
    public function create($url = '')
    {
        return new UrlBuilder($url);
    }
}
