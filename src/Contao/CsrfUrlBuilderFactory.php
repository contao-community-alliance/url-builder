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

namespace ContaoCommunityAlliance\UrlBuilder\Contao;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilderFactoryInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * URL builder factory for URLs with security token.
 *
 * @package ContaoCommunityAlliance\UrlBuilder\Contao
 */
class CsrfUrlBuilderFactory implements UrlBuilderFactoryInterface
{
    /**
     * The token manager.
     *
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;

    /**
     * The token name.
     *
     * @var string
     */
    private $tokenName;

    /**
     * CsrfUrlBuilder constructor.
     *
     * @param CsrfTokenManagerInterface $tokenManager The token manager.
     * @param string                    $tokenName    The token name.
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager, $tokenName)
    {
        $this->tokenManager = $tokenManager;
        $this->tokenName    = $tokenName;
    }

    /**
     * Retrieve an instance.
     *
     * @param string $url The initial URL.
     *
     * @return CsrfUrlBuilder
     */
    public function create($url = '')
    {
        return new CsrfUrlBuilder($this->tokenManager, $this->tokenName, $url);
    }
}
