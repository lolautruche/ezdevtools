<?php
/**
 * File containing the ezpDevToolsContentModel class
 *
 * @copyright Copyright (C) 2011 Jérôme Vieilledent. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License v3
 * @version //autogentag//
 */

class ezpDevToolsContentModel
{
    /**
     * Returns a list of content classes, grouped by class group, as raw data in an array
     *
     * @return array
     */
    public function getContentClassesList()
    {
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

        return $finalClassGroups;
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
