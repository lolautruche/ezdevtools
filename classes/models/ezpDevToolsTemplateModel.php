<?php
/**
 * File containing the ezpDevToolsTemplateModel class
 *
 * @copyright Copyright (C) 2011 Jérôme Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License v3
 * @version //autogentag//
 */

class ezpDevToolsTemplateModel
{
    /**
     * @var eZTemplate
     */
    private $tpl;

    public function __construct()
    {
        $this->tpl = eZTemplate::factory();
    }

    /**
     * Returns a sorted list of template operators
     *
     * @return array
     * @todo Return more useful info like operator hints
     */
    public function getOperatorsList()
    {
        $operators = array_keys( $this->tpl->Operators );
        sort( $operators );
        return $operators;
    }

    /**
     * Returns a sorted list of template functions
     *
     * @return array
     */
    public function getFunctionList()
    {
        $functions = array_keys( $this->tpl->Functions );
        sort( $functions );
        return $functions;
    }

    /**
     * Returns $operatorName's params
     *
     * @param string $operatorName
     * @return array
     */
    public function getOperatorParams( $operatorName )
    {
        return $this->tpl->operatorParameterList( $operatorName );
    }
}
