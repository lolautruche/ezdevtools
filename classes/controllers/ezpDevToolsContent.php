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
     * Expected Response groups for content class list viewing
     * @var string
     */
    const VIEWLIST_RESPONSEGROUP_FIELDS = 'Fields';

    /**
     * Returns a list of content classes, grouped by class group
     *
     * @return ezpRestMvcResult
     */
    public function doListClasses()
    {
        $result = new ezpRestMvcResult;
        $finalClassGroups = array();

        $classGroupList = eZContentClassGroup::fetchList();
        if ( is_array( $classGroupList ) )
        {
            foreach ( $classGroupList as $classGroup )
            {
                $groupName = $classGroup->attribute( 'name' );
                $finalClassGroups[$groupName] = array();
                $classList = eZContentClassClassGroup::fetchClassList(
                    eZContentClass::VERSION_STATUS_DEFINED,
                    $classGroup->attribute( 'id' )
                );
                foreach ( $classList as $class )
                {
                    $classItem = array(
                        'name' => $class->attribute( 'name' ),
                        'identifier' => $class->attribute( 'identifier' ),
                        'id' => (int)$class->attribute( 'id' ),
                        'isContainer' => (bool)$class->attribute( 'is_container' ),
                        'alwaysAvailable' => (bool)$class->attribute( 'always_available' ),
                        'contentObjectName' => $class->attribute( 'contentobject_name' ),
                        'remoteId' => $class->attribute( 'remote_id' ),
                        'fields' => $this->getFieldsForClass( $class )
                    );

                    $finalClassGroups[$groupName][] = $classItem;
                }
            }
        }

        $result->variables['contentClasses'] = $finalClassGroups;
        return $result;
    }

    /**
     * Returns fields information for $class
     *
     * @param eZContentClass $class
     * @return array
     */
    private function getFieldsForClass( eZContentClass $class )
    {
        $fields = array();
        foreach ( $class->attribute( 'data_map' ) as $identifier => $classAttribute )
        {
            $fields[$identifier][] = array(
                'id' => (int)$classAttribute->attribute( 'id' ),
                'name' => $classAttribute->attribute( 'name' ),
                'type' => $classAttribute->attribute( 'data_type_string' ),
                'isRequired' => (bool)$classAttribute->attribute( 'is_required' ),
                'isInformationCollector' => (bool)$classAttribute->attribute( 'is_information_collector' ),
                'isSearchable' => (bool)$classAttribute->attribute( 'is_searchable' ),
                'isTranslatable' => (bool)$classAttribute->attribute( 'can_translate' )
            );
        }

        return $fields;
    }
}
