<?php
/**
 * Router script for running eZ Publish CMS on top of PHP 5.4 built-in webserver
 * WARNING !!! Use it for DEVELOPMENT purpose ONLY !!!
 * This script is provided as is, use it at your own risk !
 *
 * @copyright Copyright (C) 2011 Jérôme Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

if ( !isset( $_SERVER['SERVER_PROTOCOL'] ) )
{
    $message = <<<EOF
This is a router script to be used with built-in PHP server (available as of PHP 5.4.0).
It will set up all needed rewrite rules for eZ Publish.

Usage
-----
From your command line, type :

    $ cd /path/to/ezpublish/root
    $ php -S localhost:8000 extension/ezdevtools/bin/php/router.php

This will start PHP webserver for localhost on port 8000.
You can of course replace localhost by another host. Port is also customizable.

For more information on PHP webserver, see http://php.net/manual/en/features.commandline.webserver.php

EOF;
    echo $message;
    exit;
}

// Determine which script to redirect to depending on rewrite rule to apply
// If the request needs to be served directly, $script will be null
// $script defaults to index.php, just like in regular Apache rewrite rules for eZ Publish
$uri = $_SERVER['REQUEST_URI'];
$phpSelf = $_SERVER['PHP_SELF'];
$script = 'index.php';

// REST API
if ( strpos( $uri, '/api/' ) === 0 )
{
    $script = 'index_rest.php';
}
// Tree menu in admin
else if ( preg_match( '#^/([^/]+/)?content/treemenu.*#', $uri ) )
{
    $script = 'index_treemenu.php';
}
// No script redirection for following patterns.
// Regexp conditions at the end in order to be faster if strpos() matches.
else if (
    strpos( $uri, '/share/icons/' ) === 0 ||
    strpos( $uri, '/var/storage/packages/' ) === 0 ||
    strpos( $uri, '/favicon.ico' ) === 0 ||
    strpos( $uri, '/design/standard/images/favicon.ico' ) === 0 ||
    strpos( $uri, '/robots.txt' ) === 0 ||
    strpos( $uri, '/w3c/p3p.xml' ) === 0 ||
    preg_match( '#^/extension/[^/]+/design/[^/]+/(stylesheets|flash|images|lib|javascripts?)/.*#', $uri ) ||
    preg_match( '#^/design/[^/]+/(stylesheets|images|javascript)/.*#', $uri ) ||
    preg_match( '#^/var/([^/]+/)?storage/images(-versioned)?/.*#', $uri ) ||
    preg_match( '#^/var/([^/]+/)?cache/(texttoimage|public)/.*#', $uri ) ||
    preg_match( '#^/packages/styles/.+/(stylesheets|images|javascript)/[^/]+/.*#', $uri ) ||
    preg_match( '#^/packages/styles/.+/thumbnail/.*#', $uri )
)
{
    $script = null;
}

if ( $script && file_exists( $script ) )
{
    // First setup some missing $_SERVER vars
    if ( !isset( $_SERVER['SERVER_ADDR'] ) )
        $_SERVER['SERVER_ADDR'] = gethostbyname( $_SERVER['SERVER_NAME'] );
    if ( !isset( $_SERVER['QUERY_STRING'] ) )
        $_SERVER['QUERY_STRING'] = '';
    if ( !isset( $_SERVER['SCRIPT_FILENAME'] ) )
        $_SERVER['SCRIPT_FILENAME'] = realpath( $script );
    if ( !isset( $_SERVER['CONTENT_LENGTH'] ) )
        $_SERVER['CONTENT_LENGTH'] = '';

    // Fix PHP_SELF since we deal with virtual folders, so PHP server would prepend /index.php to it
    if ( strpos( $uri, $script ) === false && strpos( $phpSelf, $script ) !== false )
    {
        $phpSelf = str_replace( "/$script", '', $phpSelf );
        $_SERVER['PHP_SELF'] = $phpSelf;
    }
    // To stick with regular Apache HTTPD behaviour, SCRIPT_NAME should equals to PHP_SELF
    $_SERVER['SCRIPT_NAME'] = $phpSelf;

    unset( $phpSelf, $uri );
    require_once( $script );
}
else
{
    unset( $phpSelf, $uri );
    return false;
}
?>
