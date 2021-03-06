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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');


/**
 * sportsmanagementModelTeamPersons
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2013
 * @access public
 */
class sportsmanagementModelTeamPersons extends JModelList
{
	var $_identifier = "teampersons";
    var $_project_id = 0;
    var $_season_id = 0;
    var $_team_id = 0;
    var $_project_team_id = 0;
    var $_persontype = 0;
    
    
    /**
     * sportsmanagementModelTeamPersons::__construct()
     * 
     * @param mixed $config
     * @return void
     */
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'ppl.lastname',
                        'ppl.person_id',
                        'ppl.project_position_id',
                        'ppl.published',
                        'ppl.ordering',
                        'ppl.picture',
                        'ppl.id',
                        'tp.market_value',
                        'tp.jerseynumber'
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
		// Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Initialise variables.
		//$app = JFactory::getApplication('administrator');
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' context -> '.$this->context.''),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        
        $value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);
        
        //$temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.search_nation', 'filter_search_nation', '');
        
        if ( JRequest::getVar('team_id') )
        {
        $this->setState('filter.team_id', JRequest::getVar('team_id') );    
        }
        else
        {
		$this->setState('filter.team_id', $app->getUserState( "$option.team_id", '0' ) );
        }
        
        if ( JRequest::getVar('persontype') )
        {
        $this->setState('filter.persontype', JRequest::getVar('persontype') );    
        }
        else
        {
        $this->setState('filter.persontype', $app->getUserState( "$option.persontype", '0' ) );
        }
        
        if ( JRequest::getVar('project_team_id') )
        {
        $this->setState('filter.project_team_id', JRequest::getVar('project_team_id') );    
        }
        else
        {
        $this->setState('filter.project_team_id', $app->getUserState( "$option.project_team_id", '0' ) );
        }
        
        $this->setState('filter.pid', $app->getUserState( "$option.pid", '0' ) );
        $this->setState('filter.season_id', $app->getUserState( "$option.season_id", '0' ) );

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('ppl.lastname', 'asc');
	}
    
    
    /**
	 * sportsmanagementModelTeamPersons::getListQuery()
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
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        
        //$search	= $this->getState('filter.search');
		        
        $this->_project_id	= $app->getUserState( "$option.pid", '0' );
//        $this->_season_id	= $app->getUserState( "$option.season_id", '0' );
//        $this->_team_id = JRequest::getVar('team_id');
//        $this->_persontype = JRequest::getVar('persontype');
//        $this->_project_team_id = JRequest::getVar('project_team_id');
//        
//        if ( !$this->_team_id )
//        {
//            $this->_team_id	= $app->getUserState( "$option.team_id", '0' );
//        }
//        if ( !$this->_project_team_id )
//        {
//            $this->_project_team_id	= $app->getUserState( "$option.project_team_id", '0' );
//        }
//        if ( empty($this->_persontype) )
//        {
//            $this->_persontype	= $app->getUserState( "$option.persontype", '0' );
//        }
        
//        // Get the WHERE and ORDER BY clauses for the query
//		$where = self::_buildContentWhere();
//		$orderby = self::_buildContentOrderBy();
        
//        if ( COM_SPORTSMANAGEMENT_USE_NEW_TABLE )
//        {
            
        //$query->select('ppl.firstname','ppl.lastname','ppl.nickname','ppl.height','ppl.weight','ppl.injury','ppl.suspension','ppl.away','ppl.id','ppl.id AS person_id');
        $query->select('ppl.id,ppl.firstname,ppl.lastname,ppl.nickname,ppl.picture,ppl.id as person_id,ppl.injury,ppl.suspension,ppl.away,ppl.ordering,ppl.published,ppl.checked_out,ppl.checked_out_time  ');
		$query->select('ppl.position_id as person_position_id');
        //$query->select('pos.id as position_id');
        $query->select('tp.id as tpid, tp.market_value, tp.jerseynumber,st.id as projectteam_id');
		$query->select('u.name AS editor');
        $query->select('st.season_id AS season_id');
        $query->select('ppos.id as project_position_id');
        //$query->select('tp.project_position_id as project_position_id');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_person AS ppl');
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_person_id AS tp on tp.person_id = ppl.id');
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id AS st on st.team_id = tp.team_id and st.season_id = tp.season_id');
        
        $query->join('LEFT', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_person_project_position AS ppp on ppp.person_id = ppl.id');
        
        $query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_position AS ppos ON ppos.id = ppp.project_position_id ');
        //$query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_position AS pos ON pos.id = ppos.position_id ');
        //$query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_position AS pos ON pos.id = ppl.position_id ');
        
        $query->join('LEFT', '#__users AS u ON u.id = tp.checked_out');
//        }
//        else
//        {    
//        $query->select('ppl.firstname','ppl.lastname','ppl.nickname','ppl.height','ppl.weight','ppl.id','ppl.id AS person_id');
//		$query->select('tp.*','tp.id as tpid');
//		$query->select('u.name AS editor');
//        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_person AS ppl');
//        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_team_player AS tp on tp.person_id = ppl.id');
//        $query->join('LEFT', '#__users AS u ON u.id = tp.checked_out');
//        }

        
//        if ($where)
//        {
//            $query->where($where);
//        }
//        if ($orderby)
//        {
//            $query->order($orderby);
//        }


        $query->where("ppl.published = 1");
        $query->where('st.team_id = '.$this->getState('filter.team_id') );
        $query->where('st.season_id = '.$this->getState('filter.season_id') );
        $query->where('tp.season_id = '.$this->getState('filter.season_id') );
        $query->where('tp.persontype = '.$this->getState('filter.persontype') );
        
        $query->where('ppp.persontype = '.$this->getState('filter.persontype') );
        
        $query->where('ppp.project_id = '.$this->_project_id );
        
        if ($this->getState('filter.search'))
		{
        $query->where('(LOWER(ppl.lastname) LIKE ' . $db->Quote( '%' . $this->getState('filter.search') . '%' ).
						   'OR LOWER(ppl.firstname) LIKE ' . $db->Quote( '%' . $this->getState('filter.search') . '%' ) .
						   'OR LOWER(ppl.nickname) LIKE ' . $db->Quote( '%' . $this->getState('filter.search') . '%' ) . ')');
        }
            
        $query->order($db->escape($this->getState('list.ordering', 'ppl.lastname')).' '.
                $db->escape($this->getState('list.direction', 'ASC')));
                
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        }
        
        return $query;
	}
    
    
    
    /**
     * sportsmanagementModelTeamPersons::checkProjectPositions()
     * 
     * @param mixed $items
     * @param integer $project_id
     * @return
     */
    function checkProjectPositions($items=NULL,$project_id=0)
    {
        // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Create a new query object.
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
        $date = JFactory::getDate();
	    $user = JFactory::getUser();
        $modified = $date->toSql();
	    $modified_by = $user->get('id');
       
        $mdl = JModelLegacy::getInstance("Projectposition", "sportsmanagementModel");
        $mdlTable = $mdl->getTable();
        $mdl = JModelLegacy::getInstance("seasonteamperson", "sportsmanagementModel");
        $mdlSTP = $mdl->getTable();
                
        foreach( $items as $row)
        {
        if ( $row->person_position_id)
        {
        $query->clear();
        $query->select('id'); 
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_position');
        $query->where('project_id = '.$project_id);
        $query->where('position_id = '.$row->person_position_id);
        $db->setQuery($query);
        $result = $db->loadResult();
        if (!$result)
		{
		$mdlTable->project_id = $project_id;
        $mdlTable->position_id = $row->person_position_id;
        $mdlTable->modified = $db->Quote(''.$modified.'');
        $mdlTable->modified_by = $modified_by;  
        $mdlTable->published = 1;
        
        if ($mdlTable->store()===false)
		{
        $app->enqueueMessage(__METHOD__.' '.__LINE__.' message<br><pre>'.print_r($db->getErrorMsg(), true).'</pre><br>','Error');
		}
		else
		{
		$row->project_position_id = $db->insertid();  
        $mdlSTP->load($row->tpid);  
        $mdlSTP->project_position_id = $db->insertid(); 
        if ($mdlSTP->store()===false)
		{
        $app->enqueueMessage(__METHOD__.' '.__LINE__.' message<br><pre>'.print_r($db->getErrorMsg(), true).'</pre><br>','Error');
		}
		else
		{
		}
        
		}

		}
           
        }   
         
        }
        
        return $items;
    }



	/**
	 * sportsmanagementModelTeamPersons::getProjectTeamplayers()
	 * 
	 * @param mixed $project_team_id
	 * @return
	 */
	function getProjectTeamplayers($team_id = 0,$season_id = 0)
    {
        // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Create a new query object.
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser(); 
		
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' project_team_id -> '.$project_team_id.'<br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' season_id -> '.$season_id.'<br>','Notice');
        
        // Select some fields
		$query->select('ppl.*');
        // From table
//		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_person as pl');
//        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_team_player as tpl on tpl.person_id = pl.id');
//        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team as pt on pt.id = tpl.projectteam_id');
//        $query->where('pt.team_id = '.$team_id);
        
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_person AS ppl');
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_person_id AS tp on tp.person_id = ppl.id');
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id AS st on st.team_id = tp.team_id');
        $query->where('st.team_id = '.$team_id);
        $query->where('st.season_id = '.$season_id);
        $query->where('tp.team_id = '.$season_id);
        
        $db->setQuery($query);
        //$db->query();
        $result = $db->loadObjectList();
        
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query->dump(), true).'</pre><br>','Notice');
                
		if (!$result)
		{
            //$app->enqueueMessage(__METHOD__.' '.__LINE__.' message<br><pre>'.print_r($db->getErrorMsg(), true).'</pre><br>','Error');
            //$app->enqueueMessage(__METHOD__.' '.__LINE__.' nummer<br><pre>'.print_r($db->getErrorNum(), true).'</pre><br>','Error');
			return false;
		}
		return $result;
    }

	
	

	

	

	
	/**
	 * remove specified players from team
	 * @param $cids player ids
	 * @return int count of removed
	 */
	function remove($cids)
	{
		$count=0;
		foreach($cids as $cid)
		{
			$object=&$this->getTable('teamplayer');
			if ($object->canDelete($cid) && $object->delete($cid))
			{
				$count++;
			}
			else
			{
				$this->setError(JText::sprintf('COM_SPORTSMANAGEMENT_ADMIN_TEAMSTAFFS_MODEL_ERROR_REMOVE_TEAMPLAYER',$object->getError()));
			}
		}
		return $count;
	}

}
?>
