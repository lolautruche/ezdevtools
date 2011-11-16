<?php
/**
 * File containing the ezpDevToolsProvider class
 *
 * @copyright Copyright (C) 2011 Jérôme Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License v3
 * @version //autogentag//
 */

class ezpDevToolsProvider implements ezpRestProviderInterface, ezpRestViewControllerInterface
{
    public function getRoutes()
    {
        $routes = array(
            'ezpDevToolsClasses' => new ezpRestVersionedRoute(
                new ezpMvcRailsRoute( '/classes/list', 'ezpDevToolsContent', 'listClasses' ),
                1
            )
        );

        return $routes;
    }

    public function getViewController()
    {
        return $this;
    }

    public function loadView( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
    {
        return new ezpRestJsonView( $request, $result );
    }
}
