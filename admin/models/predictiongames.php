<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/


// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.modellist' );


/**
 * sportsmanagementModelPredictionGames
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelPredictionGames extends JModelList
{
	var $_identifier = "predgames";
	
    
    /**
     * sportsmanagementModelPredictionGames::__construct()
     * 
     * @param mixed $config
     * @return void
     */
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'pre.name',
                        'pre.published',
                        'pre.id',
                        'pre.ordering',
                        'pre.modified',
                        'pre.modified_by'
                        );
                parent::__construct($config);
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
		// Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Initialise variables.
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' context ->'.$this->context.''),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('pre.name', 'asc');
	}
    
	/**
	 * sportsmanagementModelPredictionGames::getListQuery()
	 * 
	 * @return
	 */
	function getListQuery()
	{
		// Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        
        $prediction_id = $app->getUserState( "$option.prediction_id", '0' );
        
        
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        $query->select(array('pre.*', 'u.name AS editor,u1.username'))
        ->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_game AS pre')
        ->join('LEFT', '#__users AS u ON u.id = pre.checked_out')
        ->join('LEFT', '#__users AS u1 ON u1.id = pre.modified_by');

        if ( $prediction_id > 0 )
		{
			$query->where('pre.id = ' . $prediction_id);
		}
        if ( $this->getState('filter.search') )
		{
			$query->where("LOWER(pre.name) LIKE " . $db->Quote('%'.$this->getState('filter.search').'%'));
		}
        if (is_numeric($this->getState('filter.state')))
		{
        $query->where('pre.published = '.$this->getState('filter.state'));
        }
        
        $query->order($db->escape($this->getState('list.ordering', 'pre.name')).' '.
                $db->escape($this->getState('list.direction', 'ASC')));
 
if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $my_text .= ' <br><pre>'.print_r($query->dump(),true).'</pre>';    
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text); 
        }

		
		return $query;
	}


	/**
	 * sportsmanagementModelPredictionGames::getChilds()
	 * 
	 * @param mixed $pred_id
	 * @param bool $all
	 * @return
	 */
	function getChilds( $pred_id, $all = false )
	{
	   // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        $starttime = microtime(); 
        
		$what = 'pro.*';
		if ( $all )
		{
			$what = 'pro.project_id';
		}
        
        // Select some fields
        $query->select($what);
        $query->select('joo.name as project_name');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_project AS pro ');
        $query->join('LEFT', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_project AS joo ON joo.id = pro.project_id');
        $query->where('pro.prediction_id = ' . $pred_id);
        $query->where('pro.project_id != 0');
        
		$db->setQuery( $query );
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
		if ( $all )
		{
			return $db->loadResultArray();
		}
		return $db->loadAssocList( 'id' );
	}

	/**
	 * sportsmanagementModelPredictionGames::getAdmins()
	 * 
	 * @param mixed $pred_id
	 * @param bool $list
	 * @return
	 */
	static function getAdmins( $pred_id, $list = false )
	{
	   // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        
		$as_what = '';
		if ( $list )
		{
			$as_what = ' AS value';
		}
        
        // Select some fields
        $query->select('user_id' . $as_what);
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_admin ');
        $query->where('prediction_id = ' . $pred_id);

		$db->setQuery( $query );
		if ( $list )
		{
			return $db->loadObjectList();
		}
		else
		{
			return $db->loadResultArray();
		}
	}

	/**
	* Method to return a prediction games array
	*
	* @access  public
	* @return  array
	* @since 0.1
	*/
	function getPredictionGames()
	{
	   // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        
        // Select some fields
        $query->select('id AS value, name AS text');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_game ');
        $query->order('name');

		$db->setQuery( $query );

		if ( !$result = $db->loadObjectList() )
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
		else
		{
			return $result;
		}
	}

}
?>