<?php
/**
 * File containing the ezpDevToolsContent class
 *
 * @copyright Copyright (C) 2011 Jérôme Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License v3
 * @version //autogentag//
 */

/**
 * Dev tools controller related to content
 */
class ezpDevToolsContent extends ezpRestMvcController
{
    /**
     * Model for this controller
     *
     * @var ezpDevToolsContentModel
     */
    private $model;

    public function __construct( $action, ezcMvcRequest $request )
    {
        parent::__construct( $action, $request );
        $this->model = new ezpDevToolsContentModel;
    }

    /**
     * Returns a list of content classes, grouped by class group
     *
     * @return ezpRestMvcResult
     */
    public function doListClasses()
    {
        $result = new ezpRestMvcResult;
        $result->variables['contentClasses'] = $this->model->getContentClassesList();
        return $result;
    }


}
