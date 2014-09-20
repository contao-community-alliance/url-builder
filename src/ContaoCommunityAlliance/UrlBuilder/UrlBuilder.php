<?php
/**
 * The Contao Community Alliance url-builder library allows easy generating and manipulation of urls.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\UrlBuilder
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
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
     * @var string
     */
    protected $scheme;

    /**
     * The host.
     *
     * @var string
     */
    protected $host;

    /**
     * The port.
     *
     * @var int
     */
    protected $port;

    /**
     * The username (if any).
     *
     * @var string
     */
    protected $user;

    /**
     * The password (if any).
     *
     * @var string
     */
    protected $pass;

    /**
     * The path of the url.
     *
     * @var string
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
     */
    public function __construct($url = '')
    {
        $parsed = parse_url($url);

        // If only one field present it is the path which must be mapped to the query string.
        if ((count($parsed) === 1) && isset($parsed['path'])) {
            $parsed = array(
                'query' => $parsed['path']
            );
        }

        if (isset($parsed['scheme'])) {
            $this->setScheme($parsed['scheme']);
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
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the user to use.
     *
     * @param string $user The user.
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
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set the password.
     *
     * @param string $pass The password.
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
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Set the scheme.
     *
     * @param string $scheme The scheme.
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
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the hostname.
     *
     * @param string $host The hostname.
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
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the port.
     *
     * @param int $port The port.
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
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path.
     *
     * @param string $path The path.
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
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Set the fragment.
     *
     * @param string $fragment The fragment.
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
        $this->query[$name] = $value;

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
            array($name => $value),
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
            $this->insertQueryParameter($name, $value, $index);
        } else {
            $this->setQueryParameter($name, $value);
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
        $queryString = parse_url($url, PHP_URL_QUERY);
        $this->addQueryParameters($queryString);

        return $this;
    }

    /**
     * Retrieve the serialized query string.
     *
     * @return string
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

        return $query;
    }

    /**
     * Retrieve the base url.
     *
     * The base URL is the url without query part and fragment.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $url = '';
        if (isset($this->scheme)) {
            $url .= $this->scheme . '://';
        }

        if (isset($this->user)) {
            $url .= $this->user;

            if (isset($this->pass)) {
                $url .= ':' . $this->pass;
            }

            $url .= '@';
        }

        if (isset($this->host)) {
            $url .= $this->host;

            if (isset($this->port)) {
                $url .= ':' . $this->port;
            }
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

        if (count($this->query)) {
            if ($url) {
                $url .= '?';
            }

            $url .= $this->getQueryString();
        }

        if (isset($this->fragment)) {
            $url .= '#' . $this->fragment;
        }

        return $url;
    }
}
