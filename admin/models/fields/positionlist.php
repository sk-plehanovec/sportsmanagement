<?php
/**
 * @copyright	Copyright (C) 2013 fussballineuropa.de. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

/**
 * Session form field class
 */
class JFormFieldpositionlist extends JFormFieldList
{
	/**
	 * field type
	 * @var string
	 */
	public $type = 'positionlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
    
    $db = &JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('pos.id AS value, pos.name AS text');
			$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_position as pos');
			$query->join('inner', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_sports_type AS s ON s.id=pos.sports_type_id');
			$query->where('pos.published=1');
			$query->order('pos.ordering,pos.name');
			$db->setQuery($query);
			$options = $db->loadObjectList();
    
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}