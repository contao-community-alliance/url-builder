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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2014-2017 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/url-builder/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder\Contao;

use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * URL builder with security token.
 *
 * @package ContaoCommunityAlliance\UrlBuilder\Contao
 */
class CsrfUrlBuilder extends UrlBuilder
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
     * @param string                    $url          The url.
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager, $tokenName, $url = '')
    {
        parent::__construct($url);

        $this->tokenManager = $tokenManager;
        $this->tokenName    = $tokenName;
    }

    /**
     * Retrieve the serialized query string.
     *
     * @return string
     */
    public function getQueryString()
    {
        $query = parent::getQueryString();
        if ($query) {
            $query .= '&';
        }

        $query .= 'rt=' . $this->tokenManager->getToken($this->tokenName)->getValue();

        return $query;
    }
}
