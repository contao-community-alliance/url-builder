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
 * @package    contao-community-alliance/url-builder
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Bocharsky Victor <bocharsky.bw@gmail.com>
 * @copyright  2014-2016 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/url-builder/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\UrlBuilder;

/**
 * General purpose URL builder class.
 *
 * @package ContaoCommunityAlliance\UrlBuilder
 */
class UrlBuilder
{
    /**
     * The scheme.
     *
     * @var string|null
     */
    protected $scheme;

    /**
     * The host.
     *
     * @var string|null
     */
    protected $host;

    /**
     * The port.
     *
     * @var int|null
     */
    protected $port;

    /**
     * The username (if any).
     *
     * @var string|null
     */
    protected $user;

    /**
     * The password (if any).
     *
     * @var string|null
     */
    protected $pass;

    /**
     * The path of the url.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The query parameters.
     *
     * @var string[]
     */
    protected $query = array();

    /**
     * The part after the hash.
     *
     * @var string
     */
    protected $fragment;

    /**
     * Create a new instance.
     *
     * @param string $url The url to start with.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function __construct($url = '')
    {
        // If only one field present it is the path which must be mapped to the query string.
        $parsed = $this->parseUrl($url);

        if (isset($parsed['scheme'])) {
            $this->setScheme($parsed['scheme']);
        } elseif ('//' === substr($url, 0, 2)) {
            $this->setScheme('');
        }

        if (isset($parsed['host'])) {
            $this->setHost($parsed['host']);
        }

        if (isset($parsed['port'])) {
            $this->setPort($parsed['port']);
        }

        if (isset($parsed['user'])) {
            $this->setUser($parsed['user']);
        }

        if (isset($parsed['pass'])) {
            $this->setPass($parsed['pass']);
        }

        if (isset($parsed['path'])) {
            $this->setPath($parsed['path']);
        }

        if (isset($parsed['query'])) {
            $this->addQueryParameters($parsed['query']);
        }

        if (isset($parsed['fragment'])) {
            $this->setFragment($parsed['fragment']);
        }
    }

    /**
     * Create a new instance with the given URL.
     *
     * @param string $url The URL to start with.
     *
     * @return UrlBuilder
     */
    public static function fromUrl($url)
    {
        return new static($url);
    }

    /**
     * Get the user to use.
     *
     * @return string|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the user to use.
     *
     * @param string|null $user The user.
     *
     * @return UrlBuilder
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Retrieve the password.
     *
     * @return string|null
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set the password.
     *
     * @param string|null $pass The password.
     *
     * @return UrlBuilder
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get the scheme.
     *
     * @return string|null
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Set the scheme.
     *
     * @param string|null $scheme The scheme.
     *
     * @return UrlBuilder
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Get the hostname.
     *
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the hostname.
     *
     * @param string|null $host The hostname.
     *
     * @return UrlBuilder
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Retrieve the port.
     *
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the port.
     *
     * @param int|null $port The port.
     *
     * @return UrlBuilder
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Retrieve the path.
     *
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path.
     *
     * @param string|null $path The path.
     *
     * @return UrlBuilder
     */
    public function setPath($path)
    {
        // Replace 2 or more slashes together.
        $this->path = preg_replace('@/{2,}@', '/', $path);

        return $this;
    }

    /**
     * Retrieve the fragment.
     *
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Set the fragment.
     *
     * @param string|null $fragment The fragment.
     *
     * @return UrlBuilder
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Set a query parameter.
     *
     * @param string $name  The name of the query parameter.
     *
     * @param string $value The value of the query parameter.
     *
     * @return UrlBuilder
     */
    public function setQueryParameter($name, $value)
    {
        $this->query[(string) $name] = (string) $value;

        return $this;
    }

    /**
     * Insert a query parameter at the given position.
     *
     * @param string $name     The name of the query parameter.
     *
     * @param string $value    The value of the query parameter.
     *
     * @param int    $position The desired position where the query parameter shall get inserted at.
     *
     * @return UrlBuilder
     */
    public function insertQueryParameter($name, $value, $position)
    {
        $this->query = array_merge(
            array_slice($this->query, 0, $position),
            array((string) $name => (string) $value),
            array_slice($this->query, $position)
        );

        return $this;
    }

    /**
     * Insert a query parameter at the given position.
     *
     * @param string $name   The name of the query parameter.
     *
     * @param string $value  The value of the query parameter.
     *
     * @param string $before The name of the desired parameter where the query parameter shall get inserted before.
     *
     * @return UrlBuilder
     */
    public function insertQueryParameterBefore($name, $value, $before)
    {
        $index = array_search($before, array_keys($this->query));

        if ($index !== false) {
            $this->insertQueryParameter((string) $name, (string) $value, $index);
        } else {
            $this->setQueryParameter((string) $name, (string) $value);
        }

        return $this;
    }

    /**
     * Unset a query parameter, if defined.
     *
     * @param string $name The name of the query parameter.
     *
     * @return UrlBuilder
     */
    public function unsetQueryParameter($name)
    {
        unset($this->query[$name]);

        return $this;
    }

    /**
     * Check if a query parameter is defined.
     *
     * @param string $name The name of the query parameter.
     *
     * @return bool
     */
    public function hasQueryParameter($name)
    {
        return isset($this->query[$name]);
    }

    /**
     * Retrieve the value of a query parameter.
     *
     * @param string $name The name of the query parameter.
     *
     * @return string|null
     */
    public function getQueryParameter($name)
    {
        return isset($this->query[$name]) ? $this->query[$name] : null;
    }

    /**
     * Absorb the query parameters from a query string.
     *
     * @param string $queryString The query string.
     *
     * @return UrlBuilder
     */
    public function addQueryParameters($queryString)
    {
        $queries = preg_split('/&(amp;)?/i', $queryString);

        foreach ($queries as $v) {
            $explode = explode('=', $v);

            $name  = $explode[0];
            $value = isset($explode[1]) ? $explode[1] : '';
            $rpos  = strrpos($name, '?');

            if ($rpos !== false) {
                $name = substr($name, ($rpos + 1));
            }

            if (empty($name)) {
                continue;
            }

            $this->setQueryParameter($name, $value);
        }

        return $this;
    }

    /**
     * Absorb the query parameters from a URL.
     *
     * @param string $url The URL to absorb the parameters from.
     *
     * @return UrlBuilder
     */
    public function addQueryParametersFromUrl($url)
    {
        $this->addQueryParameters(static::fromUrl($url)->getQueryString());

        return $this;
    }

    /**
     * Retrieve the serialized query string.
     *
     * @return string|null
     */
    public function getQueryString()
    {
        $query = '';

        foreach ($this->query as $name => $value) {
            if ($query) {
                $query .= '&';
            }

            $query .= $name;
            if ($value) {
                $query .= '=' . $value;
            }
        }

        if ('' === $query) {
            return null;
        }

        return $query;
    }

    /**
     * Retrieve the base url.
     *
     * The base URL is the url without query part and fragment.
     *
     * @return string|null
     */
    public function getBaseUrl()
    {
        $url = '';
        if (isset($this->scheme)) {
            if ('' !== $this->scheme) {
                $url .= $this->scheme . ':';
            }
            $url .= '//';
        }

        if (isset($this->user)) {
            $url .= $this->user;

            if (isset($this->pass)) {
                $url .= ':' . $this->pass;
            }

            $url .= '@';
        }

        $url .= $this->host;

        if (isset($this->port)) {
            $url .= ':' . $this->port;
        }

        if (isset($this->path)) {
            if ($url != '' && $this->path[0] !== '/') {
                $url .= '/';
            }

            $url .= $this->path;
        }

        return $url;
    }

    /**
     * Retrieve the complete generated URL.
     *
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getBaseUrl();

        if ($query = $this->getQueryString()) {
            if ($url) {
                $url .= '?';
            }

            $url .= $query;
        }

        if (isset($this->fragment)) {
            $url .= '#' . $this->fragment;
        }

        return $url;
    }

    /**
     * Parse the URL and fix up if it only contains only one element to make it the query element.
     *
     * @param string $url The url to parse.
     *
     * @return array
     */
    private function parseUrl($url)
    {
        $parsed = parse_url($url);

        if ((count($parsed) === 1)
            && isset($parsed['path'])
            && (0 === strpos($parsed['path'], '?') || false !== strpos($parsed['path'], '&'))
        ) {
            $parsed = array(
                'query' => $parsed['path']
            );

            return $parsed;
        }

        return $parsed;
    }
}
