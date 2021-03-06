<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroups.php
* @author                diddipoeler, stony und svdoldie (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
*        SportsManagement is free software: you can redistribute it and/or modify
*        it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  SportsManagement is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
*  Diese Datei ist Teil von SportsManagement.
*
*  SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
*  der GNU General Public License, wie von der Free Software Foundation,
*  Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
*  ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
*  SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
*  OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
*  Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
*  Siehe die GNU General Public License f�r weitere Details.
*
*  Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
*  Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');
//require_once (JPATH_COMPONENT.DS.'models'.DS.'list.php');

/**
 * Sportsmanagement Component sportstypes Model
 *
 * @package	Sportsmanagement
 * @since	1.5
 */
class sportsmanagementModelSportsTypes extends JModelList
{
	var $_identifier = "sportstypes";
    //var $setError = '';
    
    /**
     * sportsmanagementModelSportsTypes::__construct()
     * 
     * @param mixed $config
     * @return void
     */
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        's.name',
                        's.icon',
                        's.sportsart',
                        's.id',
                        's.ordering',
                        's.checked_out',
                        's.checked_out_time'
                        );
                parent::__construct($config);
                $getDBConnection = sportsmanagementHelper::getDBConnection();
                parent::setDbo($getDBConnection);
        }
        
    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        // Initialise variables.
		$app = JFactory::getApplication('administrator');
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' context -> '.$this->context.''),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

//		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
//		$this->setState('filter.state', $published);

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('s.name', 'asc');
	}
    

	/**
	 * sportsmanagementModelSportsTypes::getListQuery()
	 * 
	 * @return
	 */
	function getListQuery()
	{
		$app = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        //$search	= $this->getState('filter.search');
        // Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser(); 
  
        // Select some fields
		$query->select(implode(",",$this->filter_fields));
        // From table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_sports_type AS s');
        $query->join('LEFT', '#__users AS uc ON uc.id = s.checked_out');
        
        if ($this->getState('filter.search'))
		{
        $query->where('LOWER(s.name) LIKE '.$db->Quote('%'.$this->getState('filter.search').'%'));
        }
        
        $query->order($db->escape($this->getState('list.ordering', 's.name')).' '.
                $db->escape($this->getState('list.direction', 'ASC')));
                
		if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        }
        
		return $query;
        
	}




	/**
	 * Method to return a sportsTypes array (id,name)
	 *
	 * @access	public
	 * @return	array
	 * @since	1.5.0a
	 */
	public static function getSportsTypes()
	{
		$app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $query	= $db->getQuery(true);
        $query->select('id, name, name AS text');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_sports_type');
        $query->order('name ASC');
		//$query='SELECT id, name, name AS text FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_sports_type ORDER BY name ASC ';
		$db->setQuery($query);
		if ( !$result = $db->loadObjectList() )
		{
			//$this->setError($db->getErrorMsg());COM_SPORTSMANAGEMENT_ADMIN_SPORTSTYPES_NO_RESULT
            //$app->enqueueMessage(JText::_('getSportsTypes<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
            $app->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_ADMIN_SPORTSTYPES_NO_RESULT'),'Error');
			return array();
		}
		foreach ($result as $sportstype){
			$sportstype->name = JText::_($sportstype->name);
		}
		return $result;
	}

}
?>