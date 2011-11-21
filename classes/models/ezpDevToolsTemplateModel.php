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
     * Returns a sorted list of template operators.
     * Each operator has some information :
     *  - inputAsParameter: Indicates if operator can/must be used with the pipe syntax
     *                      by taking the input variable as first parameter. (i.e. $myString|i18n( ... ))
     *                      Values can be either true, false, null (unknown) or "always" (in this case, this syntax is mandatory)
     *  - isPHPFunction: Indicates if operator is a wrapper for a native PHP function.
     *                   Values can be true or false
     *
     * @return array
     */
    public function getOperatorsList()
    {
        $operators = array();
        $operatorObjects = array();
        foreach ( $this->tpl->Operators as $operatorName => $operatorDefinition )
        {
            if ( isset( $operatorDefinition['class'] ) )
            {
                $operatorClass = $operatorDefinition['class'];
                if ( !isset( $operatorObjects[$operatorClass] ) )
                    $operatorObjects[$operatorClass] = new $operatorClass();

                $operatorObject = $operatorObjects[$operatorClass];
                $operators[$operatorName] = array(
                    'inputAsParameter' => null,
                    'isPHPFunction' => false
                );
                if ( method_exists( $operatorObject, 'operatorTemplateHints' ) )
                {
                    $hints = $operatorObject->operatorTemplateHints();
                    if ( isset( $hints[$operatorName] ) )
                    {
                        if ( isset( $hints[$operatorName]['input-as-parameter'] ) )
                            $operators[$operatorName]['inputAsParameter'] = $hints[$operatorName]['input-as-parameter'];
                    }
                }
            }
            else if ( isset( $operatorDefinition['function'] ) && $operatorDefinition['function'] === 'eZPHPOperatorInit' )
            {
                $operators[$operatorName] = array(
                    'inputAsParameter' => true,
                    'isPHPFunction' => true
                );
            }

            $operators[$operatorName]['link'] = "/template/operator/$operatorName/params";
        }

        ksort( $operators );
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
