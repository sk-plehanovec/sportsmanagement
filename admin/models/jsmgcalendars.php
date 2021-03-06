<?php


defined('_JEXEC') or die();

JLoader::import('joomla.application.component.modellist');

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sportsmanagement'.DS.'tables');

class sportsmanagementModeljsmGCalendars extends JModelList 
{

	protected function _getList($query, $limitstart = 0, $limit = 0) 
    {
		$items = parent::_getList($query, $limitstart, $limit);
		if ($items === null) {
			return $items;
		}
		$tmp = array();
		foreach ($items as $item) {
			$table = JTable::getInstance('jsmGCalendar', 'sportsmanagementTable');
			$table->load($item->id);
			$tmp[] = $table;
		}
		return $tmp;
	}

	protected function getListQuery() 
    {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$user	= JFactory::getUser();

		$query->select('*');
		$calendarIDs = $this->getState('ids', null);
		if (!empty($calendarIDs)) {
			if (is_array($calendarIDs)) {
				$query->where('id IN ( '.implode(',', array_map('intval', $calendarIDs)).')');
			} else {
				$query->where($condition = 'id = '.(int)rtrim($calendarIDs, ','));
			}
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('access IN ('.$groups.')');
		}

		$query->from('#__sportsmanagement_gcalendar');
		return $query;
	}
}