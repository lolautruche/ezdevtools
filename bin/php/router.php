<?php
/**
 * Router script for running eZ Publish CMS on top of PHP 5.4 built-in webserver
 * WARNING !!! Use it for DEVELOPMENT purpose ONLY !!!
 * This script is provided as is, use it at your own risk !
 *
 * @copyright Copyright (C) 2011 J�r�me Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 *
 *
 */

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
    if ( strpos( $_SERVER['HTTP_HOST'], ':' ) )
    {
        list( $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'] ) = explode( ':', $_SERVER['HTTP_HOST'], 2 );
    }
    else
    {
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SERVER_PORT'] = '80';
    }
    $_SERVER['SERVER_ADDR'] = $_SERVER['REMOTE_ADDR'];

    if ( !isset( $_SERVER['QUERY_STRING'] ) )
        $_SERVER['QUERY_STRING'] = '';
    if ( !isset( $_SERVER['SCRIPT_FILENAME'] ) )
        $_SERVER['SCRIPT_FILENAME'] = realpath( $script );

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
