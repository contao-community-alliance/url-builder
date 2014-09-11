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

error_reporting(E_ALL);

function includeIfExists($file)
{
    return file_exists($file) ? include $file : false;
}

if ((!$loader = includeIfExists(__DIR__.'/../vendor/autoload.php'))
    && (!$loader = includeIfExists(__DIR__.'/../../../autoload.php'))
) {
    echo 'You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -sS https://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL;
    exit(1);
}

require_once __DIR__.'/ContaoCommunityAlliance/UrlBuilder/Test/TestCase.php';
