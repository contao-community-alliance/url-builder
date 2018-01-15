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
use Symfony\Component\DependencyInjection\ResettableContainerInterface;

/**
 * URL builder with security token.
 *
 * @package ContaoCommunityAlliance\UrlBuilder\Contao
 */
class CsrfUrlBuilder extends UrlBuilder
{
    /**
     * The service container.
     *
     * @var ResettableContainerInterface
     */
    protected $container;

    /**
     * CsrfUrlBuilder constructor.
     *
     * @param ResettableContainerInterface $container The service container.
     *
     * @param string                       $url       The url.
     */
    public function __construct(ResettableContainerInterface $container, $url = '')
    {
        parent::__construct($url);

        $this->container = $container;
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

        $requestToken = $this->container
            ->get('security.csrf.token_manager')
            ->getToken($this->container->getParameter('contao.csrf_token_name'))
            ->getValue();

        $query .= 'rt=' . $requestToken;

        return $query;
    }
}
