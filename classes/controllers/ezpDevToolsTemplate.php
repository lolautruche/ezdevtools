<?php
/**
 * File containing the ezpDevToolsTemplate class
 *
 * @copyright Copyright (C) 2011 Jérôme Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License v3
 * @version //autogentag//
 */

/**
 * Dev tools controller related to templates
 */
class ezpDevToolsTemplate extends ezpRestMvcController
{
    /**
     * Model for this controller
     *
     * @var ezpDevToolsTemplateModel
     */
    private $model;

    public function __construct( $action, ezcMvcRequest $request )
    {
        parent::__construct( $action, $request );
        $this->model = new ezpDevToolsTemplateModel;
    }

    /**
     * Returns the list of all template operators, with additional info when available (like operator hints).
     *
     * @return ezpRestMvcResult
     */
    public function doListOperators()
    {
        $result = new ezpRestMvcResult;
        $result->variables['operators'] = $this->model->getOperatorsList();
        return $result;
    }

    /**
     * Returns parameters for provided operator, as described in the namedParameterList() method in the operator class.
     *
     * @return ezpRestMvcResult
     */
    public function doGetOperatorParams()
    {
        $result = new ezpRestMvcResult;
        $result->variables['info'] = array(
            'operatorName' => $this->operatorName
        );
        $result->variables['params'] = $this->model->getOperatorParams( $this->operatorName );
        return $result;
    }

    /**
     * Returns the list of all template functions
     *
     * @return ezpRestMvcResult
     */
    public function doListFunctions()
    {
        $result = new ezpRestMvcResult;
        $result->variables['functions'] = $this->model->getFunctionList();
        return $result;
    }
}
