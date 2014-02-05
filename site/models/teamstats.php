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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model');

//require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

/**
 * sportsmanagementModelTeamStats
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelTeamStats extends JModel
{
	var $projectid = 0;
	var $teamid = 0;
	var $highest_home = null;
	var $highest_away = null;
	var $highestdef_home = null;
	var $highestdef_away = null;
	var $highestdraw_home = null;
	var $highestdraw_away = null;
	var $totalshome = null;
	var $totalsaway = null;
	var $matchdaytotals = null;
	var $totalrounds = null;
	var $attendanceranking = null;

	/**
	 * sportsmanagementModelTeamStats::__construct()
	 * 
	 * @return
	 */
	function __construct( )
	{
		parent::__construct();

		$this->projectid = JRequest::getInt( "p", 0 );
		$this->teamid = JRequest::getInt( "tid", 0 );
		//preload the team;
		$this->getTeam();
	}

	/**
	 * sportsmanagementModelTeamStats::getTeam()
	 * 
	 * @return
	 */
	function getTeam( )
	{
		# it should be checked if any tid is given in the params of the url
		# if ( is_null( $this->team ) )
		if ( !isset( $this->team ) )
		{
			if ( $this->teamid > 0 )
			{
				$this->team = $this->getTable( 'Team', 'sportsmanagementTable' );
				$this->team->load( $this->teamid );
			}
		}
		return $this->team;
	}

	/**
	 * sportsmanagementModelTeamStats::getHighest()
	 * 
	 * @return
	 */
	function getHighest($which)
	{
	   $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
		
			$query->select('matches.id AS matchid, t1.name AS hometeam');
            $query->select('t2.name AS guestteam');
            $query->select('team1_result AS homegoals');
            $query->select('team2_result AS guestgoals');
            $query->select('t1.id AS team1_id');
            $query->select('t2.id AS team2_id');
        
        
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match as matches ');
        
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = matches.projectteam1_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt2 ON pt2.id = matches.projectteam2_id  ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st2 ON st2.id = pt2.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t2 ON st2.team_id = t2.id ');
        
        $query->where('pt1.project_id = '.$this->projectid);
        
        $query->where('matches.published = 1');
        $query->where('alt_decision = 0');
        $query->where('(matches.cancel IS NULL OR matches.cancel = 0)');
        switch ($which)
        {
            case 'HOME':
            $query->where('t1.id = '. $this->team->id);
            $query->where('team1_result > team2_result');
            $query->order('(team1_result-team2_result) DESC');
            break;
            case 'AWAY':
            $query->where('t2.id = '. $this->team->id);
            $query->where('team2_result > team1_result');
            $query->order('(team2_result-team1_result) DESC');
            break;
        }
        
        
        
        
        
/*            
            $query = ' SELECT matches.id AS matchid, t1.name AS hometeam, '
			       . ' t2.name AS guestteam, '
			       . ' team1_result AS homegoals, '
			       . ' team2_result AS guestgoals, '
			       . ' t1.id AS team1_id, '
			       . ' t2.id AS team2_id '
			       . ' FROM #__joomleague_match as matches '
			       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
			       . ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
			       . ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
			       . ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
			       . ' WHERE pt1.project_id = '.$this->projectid
			       . ' AND published=1 '
			       . ' AND alt_decision=0 '
			       . ' AND t1.id =  '. $this->team->id
			       . ' AND team1_result > team2_result '
				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
			       . ' ORDER BY (team1_result-team2_result) DESC '
           ;
*/
            $db->setQuery($query, 0, 1);
            //$this->highest_home = $db->loadObject( );
       
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
        switch ($which)
        {
            case 'HOME':
            if ( is_null( $this->highest_home ) )
		    {
		      $this->highest_home = $db->loadObject( );
              return $this->highest_home;
		    }
            break;
            case 'AWAY':
            if ( is_null( $this->highest_away ) )
		    {
		      $this->highest_away = $db->loadObject( );
              return $this->highest_away;
		    }
            break;
        }
        
        
        if ( !$this->highest_home || !$this->highest_away)
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
        
        
        
        
        
    }

    /**
     * sportsmanagementModelTeamStats::getHighestAway()
     * 
     * @return
     */
/**
 *     function getHighestAway( )
 *     {
 *     	if ( is_null( $this->highest_away ) )
 *     	{
 * 				$query = ' SELECT matches.id AS matchid, t1.name AS hometeam, '
 * 			       . ' t2.name AS guestteam, '
 * 			       . ' team1_result AS homegoals, '
 * 			       . ' team2_result AS guestgoals, '
 * 			       . ' t1.id AS team1_id, '
 * 			       . ' t2.id AS team2_id '
 * 			       . ' FROM #__joomleague_match as matches '
 * 			       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
 * 			       . ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
 * 			       . ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
 * 			       . ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
 * 			       . ' WHERE pt1.project_id = '.$this->projectid
 * 			       . ' AND published=1 '
 * 			       . ' AND alt_decision=0 '
 * 			       . ' AND t2.id =  '. $this->team->id
 * 			       . ' AND team2_result > team1_result '
 * 				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
 * 			       . ' ORDER BY (team2_result-team1_result) DESC '
 *            ;

 *     		$this->_db->setQuery($query, 0, 1);
 *     		$this->highest_away = $this->_db->loadObject( );
 *     	}
 *     	return $this->highest_away;
 *     }
 */

    /**
     * sportsmanagementModelTeamStats::getHighestDef()
     * 
     * @return
     */
    function getHighestDef($which)
    {
        $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('matches.id AS matchid, t1.name AS hometeam');
            $query->select('t2.name AS guestteam');
            $query->select('team1_result AS homegoals');
            $query->select('team2_result AS guestgoals');
            $query->select('t1.id AS team1_id');
            $query->select('t2.id AS team2_id');
        
        
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match as matches ');
        
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = matches.projectteam1_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt2 ON pt2.id = matches.projectteam2_id  ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st2 ON st2.id = pt2.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t2 ON st2.team_id = t2.id ');
        
        $query->where('pt1.project_id = '.$this->projectid);
        
        $query->where('matches.published = 1');
        $query->where('alt_decision = 0');
        $query->where('(matches.cancel IS NULL OR matches.cancel = 0)');
        
        switch ($which)
        {
            case 'HOME':
            $query->where('t1.id = '. $this->team->id);
            $query->where('team2_result > team1_result');
            $query->order('(team2_result-team1_result) DESC');
            break;
            case 'AWAY':
            $query->where('t2.id = '. $this->team->id);
            $query->where('team1_result > team2_result');
            $query->order('(team1_result-team2_result) DESC');
            break;
        }
        
        $db->setQuery($query, 0, 1);
        
        
        
/*        
        
    	if ( is_null( $this->highestdef_home ) )
    	{
				$query = ' SELECT matches.id AS matchid, t1.name AS hometeam, '
			       . ' t2.name AS guestteam, '
			       . ' team1_result AS homegoals, '
			       . ' team2_result AS guestgoals, '
			       . ' t1.id AS team1_id, '
			       . ' t2.id AS team2_id '
			       . ' FROM #__joomleague_match as matches '
			       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
			       . ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
			       . ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
			       . ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
			       . ' WHERE pt1.project_id = '.$this->projectid
			       . ' AND published=1 '
			       . ' AND alt_decision=0 '
			       . ' AND t1.id =  '. $this->team->id
			       . ' AND team2_result > team1_result '
				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
			       . ' ORDER BY (team2_result-team1_result)  DESC '
           ;

    		$this->_db->setQuery($query, 0, 1);
    		$this->highestdef_home = $this->_db->loadObject( );
    	}
*/        
        
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
        switch ($which)
        {
            case 'HOME':
            if ( is_null( $this->highestdef_home ) )
		    {
		      $this->highestdef_home = $db->loadObject( );
              return $this->highestdef_home;
		    }
            break;
            case 'AWAY':
            if ( is_null( $this->highestdef_away ) )
		    {
		      $this->highestdef_away = $db->loadObject( );
              return $this->highestdef_away;
		    }
            break;
        }
        
        if ( !$this->highestdef_home || !$this->highestdef_away)
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
        
        
        
    	//return $this->highestdef_home;
    }

    /**
     * sportsmanagementModelTeamStats::getHighestDefAway()
     * 
     * @return
     */
/**
 *     function getHighestDefAway( )
 *     {
 *     	if ( is_null( $this->highestdef_away ) )
 *     	{
 * 				$query = ' SELECT matches.id AS matchid, t1.name AS hometeam, '
 * 			       . ' t2.name AS guestteam, '
 * 			       . ' team1_result AS homegoals, '
 * 			       . ' team2_result AS guestgoals, '
 * 			       . ' t1.id AS team1_id, '
 * 			       . ' t2.id AS team2_id '
 * 			       . ' FROM #__joomleague_match as matches '
 * 			       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
 * 			       . ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
 * 			       . ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
 * 			       . ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
 * 			       . ' WHERE pt1.project_id = '.$this->projectid
 * 			       . ' AND published=1 '
 * 			       . ' AND alt_decision=0 '
 * 			       . ' AND t2.id =  '. $this->team->id
 * 			       . ' AND team1_result > team2_result '
 * 				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
 * 			       . ' ORDER BY (team1_result-team2_result)  DESC '
 *            ;
 *     		$this->_db->setQuery($query, 0, 1);
 *     		$this->highestdef_away = $this->_db->loadObject( );
 *     	}
 *     	return $this->highestdef_away;
 *     }
 */

    /**
     * sportsmanagementModelTeamStats::getHighestDrawAway()
     * 
     * @return
     */
/**
 *     function getHighestDrawAway( )
 *     {
 *     	if ( is_null( $this->highestdraw_away ) )
 *     	{
 *     		$query = ' SELECT t1.name AS hometeam, '
 *     		. ' t2.name AS guestteam, '
 *     		. ' team1_result AS homegoals, '
 *     		. ' team2_result AS guestgoals '
 *     		. ' FROM #__joomleague_match as matches '
 *     		. ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
 *     		. ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
 *     		. ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
 *     		. ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
 *     		. ' WHERE pt1.project_id = '.$this->projectid
 *     		. ' AND published=1 '
 *     		. ' AND alt_decision=0 '
 *     		. ' AND t2.id =  '. $this->team->id
 *     		. ' AND team1_result = team2_result '
 *     		. ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
 *     		. ' ORDER BY team2_result DESC '
 *     		;
 *     		$this->_db->setQuery($query, 0, 1);
 *     		$this->highestdraw_away = $this->_db->loadObject( );
 *     	}
 *     	return $this->highestdraw_away;
 *         
 *         
 *         
 *         
 *         
 *     }
 */
    
    /**
     * sportsmanagementModelTeamStats::getHighestDrawHome()
     * 
     * @return
     */
    function getHighestDraw($which)
    {
        $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('t1.name AS hometeam');
            $query->select('t2.name AS guestteam');
            $query->select('team1_result AS homegoals');
            $query->select('team2_result AS guestgoals');
            $query->select('t1.id AS team1_id');
            $query->select('t2.id AS team2_id');
        
        
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match as matches ');
        
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = matches.projectteam1_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt2 ON pt2.id = matches.projectteam2_id  ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st2 ON st2.id = pt2.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t2 ON st2.team_id = t2.id ');
        
        $query->where('pt1.project_id = '.$this->projectid);
        $query->where('matches.published = 1');
        $query->where('alt_decision = 0');
        $query->where('(matches.cancel IS NULL OR matches.cancel = 0)');
        
        switch ($which)
        {
            case 'HOME':
            $query->where('t1.id = '. $this->team->id);
            $query->where('team2_result = team1_result');
            $query->order('team1_result DESC');
            break;
            case 'AWAY':
            $query->where('t2.id = '. $this->team->id);
            $query->where('team1_result = team2_result');
            $query->order('team2_result DESC');
            break;
        }
        
        $db->setQuery($query, 0, 1);
        



/*
    	if ( is_null( $this->highestdraw_home ) )
    	{
    		$query = ' SELECT t1.name AS hometeam, '
    		. ' t2.name AS guestteam, '
    		. ' team1_result AS homegoals, '
    		. ' team2_result AS guestgoals '
    		. ' FROM #__joomleague_match as matches '
    		. ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
    		. ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
    		. ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
    		. ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
    		. ' WHERE pt1.project_id = '.$this->projectid
    		. ' AND published=1 '
    		. ' AND alt_decision=0 '
    		. ' AND t1.id =  '. $this->team->id
    		. ' AND team2_result = team1_result '
    		. ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
    		. ' ORDER BY team1_result DESC '
    		;
    
    		$this->_db->setQuery($query, 0, 1);
    		$this->highestdraw_home = $this->_db->loadObject( );
    	}
    	return $this->highestdraw_home;
*/        
        
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
        switch ($which)
        {
            case 'HOME':
            if ( is_null( $this->highestdraw_away ) )
		    {
		      $this->highestdraw_away = $db->loadObject( );
              return $this->highestdraw_away;
		    }
            break;
            case 'AWAY':
            if ( is_null( $this->highestdraw_home ) )
		    {
		      $this->highestdraw_home = $db->loadObject( );
              return $this->highestdraw_home;
		    }
            break;
        }
        
        if ( !$this->highestdraw_away || !$this->highestdraw_home)
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
        
        
        
        
    }
    
    /**
     * sportsmanagementModelTeamStats::getNoGoalsAgainst()
     * 
     * @return
     */
    function getNoGoalsAgainst( )
    {
    	if ( (!isset( $this->nogoals_against )) || is_null( $this->nogoals_against ) )
    	{
    		$query = ' SELECT '
			       . ' COUNT( round_id ) AS totalzero, '
			       . ' SUM( t1.id = '.$this->team->id.' AND team2_result=0 ) AS homezero, '
			       . ' SUM( t2.id = '.$this->team->id.' AND team1_result=0 ) AS awayzero '
			       . ' FROM #__joomleague_match as matches '
			       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
			       . ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
			       . ' INNER JOIN #__joomleague_project_team pt2 ON pt2.id = matches.projectteam2_id '
			       . ' INNER JOIN #__joomleague_team t2 ON t2.id = pt2.team_id '
			       . ' WHERE pt1.project_id = '.$this->projectid.' '
			       . ' AND published=1 '
			       . ' AND alt_decision=0 '
			       . ' AND ((t1.id = '.$this->team->id.' AND team2_result=0 ) '
			       . ' OR  (t2.id = '.$this->team->id.' AND team1_result=0 ))'
				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
			       ;
    		$this->_db->setQuery($query);
    		$this->nogoals_against = $this->_db->loadObject( );
    	}
    	return $this->nogoals_against;
    }
    
    
    function getSeasonTotals($which)
    {
        $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        
        
        $query->select('COUNT(matches.id) AS totalmatches ');
        $query->select('COUNT(team1_result) AS playedmatches ');
//	    $query->select('IFNULL(SUM(team1_result),0) AS goalsfor,IFNULL(SUM(team2_result),0) AS goalsagainst,IFNULL(SUM(team1_result + team2_result),0) AS totalgoals,IFNULL(SUM(IF(team1_result=team2_result,1,0)),0) AS totaldraw,IFNULL(SUM(IF(team1_result<team2_result,1,0)),0) AS totalloss,IFNULL(SUM(IF(team1_result>team2_result,1,0)),0) AS totalwin  ');
		$query->select('COUNT(crowd) AS attendedmatches ');
		$query->select('SUM(crowd) AS sumspectators ');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS matches');
        
        switch ($which)
        {
            case 'HOME':
            $query->select('IFNULL(SUM(team1_result),0) AS goalsfor,IFNULL(SUM(team2_result),0) AS goalsagainst,IFNULL(SUM(team1_result + team2_result),0) AS totalgoals,IFNULL(SUM(IF(team1_result=team2_result,1,0)),0) AS totaldraw,IFNULL(SUM(IF(team1_result<team2_result,1,0)),0) AS totalloss,IFNULL(SUM(IF(team1_result>team2_result,1,0)),0) AS totalwin  ');
            $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team pt1 ON pt1.id = matches.projectteam1_id ');
            break;
            case 'AWAY':
            $query->select('IFNULL(SUM(team2_result),0) AS goalsfor,IFNULL(SUM(team1_result),0) AS goalsagainst,IFNULL(SUM(team2_result + team1_result),0) AS totalgoals,IFNULL(SUM(IF(team2_result=team1_result,1,0)),0) AS totaldraw,IFNULL(SUM(IF(team2_result<team1_result,1,0)),0) AS totalloss,IFNULL(SUM(IF(team2_result>team1_result,1,0)),0) AS totalwin  ');
            $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team pt1 ON pt1.id = matches.projectteam2_id ');
            break;
        }
        
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st ON st.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t ON st.team_id = t.id ');
        
        $query->where('pt1.project_id = '.$this->projectid);
        $query->where('published = 1');
        $query->where('t.id = '.$this->team->id);
        $query->where('(matches.cancel IS NULL OR matches.cancel = 0)');
        
        $db->setQuery($query, 0, 1);
    	//$this->totalshome = $db->loadObject();
    	
        if ( !$db->loadObject() )
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
        
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
        switch ($which)
        {
            case 'HOME':
            if ( is_null( $this->totalshome ) )
    	    {
    	       $this->totalshome = $db->loadObject();
               return $this->totalshome;
    	    }   
            break;
            case 'AWAY':
            if ( is_null( $this->totalsaway ) )
    	    {
    	       $this->totalsaway = $db->loadObject();
               return $this->totalsaway;
    	    }
            break;
        }
        
    	
        
    }
    

    /**
     * sportsmanagementModelTeamStats::getSeasonTotalsHome()
     * 
     * @return
     */
/**
 *     function getSeasonTotalsHome( )
 *     {
 *     	if ( is_null( $this->totalshome ) )
 *     	{
 *     		$query = ' SELECT '
 * 			       . ' COUNT(matches.id) AS totalmatches, '
 * 			       . ' COUNT(team1_result) AS playedmatches, '
 * 			       . ' IFNULL(SUM(team1_result),0) AS goalsfor, '
 * 			       . ' IFNULL(SUM(team2_result),0) AS goalsagainst, '
 * 			       . ' IFNULL(SUM(team1_result + team2_result),0) AS totalgoals, '
 * 			       . ' IFNULL(SUM(IF(team1_result=team2_result,1,0)),0) AS totaldraw, '
 * 			       . ' IFNULL(SUM(IF(team1_result<team2_result,1,0)),0) AS totalloss, '
 * 			       . ' IFNULL(SUM(IF(team1_result>team2_result,1,0)),0) AS totalwin, '
 * 			       . ' COUNT(crowd) AS attendedmatches, '
 * 			       . ' SUM(crowd) AS sumspectators '
 * 			       . ' FROM #__joomleague_match AS matches'
 * 			       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
 * 			       . ' WHERE pt1.project_id = '.$this->projectid
 * 			       . ' AND published=1 '
 * 			       . ' AND pt1.team_id = '.$this->team->id
 * 				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
 * 			       ;
 *     		$this->_db->setQuery($query, 0, 1);
 *     		$this->totalshome = $this->_db->loadObject();
 *     	}
 *     	return $this->totalshome;
 *     }
 */

      
    
    
    /**
     * sportsmanagementModelTeamStats::getSeasonTotalsAway()
     * 
     * @return
     */
/**
 *     function getSeasonTotalsAway( )
 *     {
 *     	if ( is_null( $this->totalsaway ) )
 *     	{
 *     		$query = ' SELECT '
 * 			       . ' COUNT(matches.id) AS totalmatches, '
 * 			       . ' COUNT(team1_result) AS playedmatches, '
 * 			       . ' IFNULL(SUM(team2_result),0) AS goalsfor, '
 * 			       . ' IFNULL(SUM(team1_result),0) AS goalsagainst, '
 * 			       . ' IFNULL(SUM(team1_result + team2_result),0) AS totalgoals, '
 * 			       . ' IFNULL(SUM(IF(team2_result=team1_result,1,0)),0) AS totaldraw, '
 * 			       . ' IFNULL(SUM(IF(team2_result<team1_result,1,0)),0) AS totalloss, '
 * 			       . ' IFNULL(SUM(IF(team2_result>team1_result,1,0)),0) AS totalwin, '
 *     				. ' COUNT(crowd) AS attendedmatches, '
 * 			       . ' SUM(crowd) AS sumspectators '
 * 			       . ' FROM #__joomleague_match AS matches'
 * 			       . ' INNER JOIN #__joomleague_project_team pt ON pt.id = matches.projectteam2_id '
 * 			       . ' WHERE pt.project_id = '.$this->projectid
 * 			       . ' AND published=1 '
 * 			       . ' AND pt.team_id = '.$this->team->id
 * 				   . ' AND (matches.cancel IS NULL OR matches.cancel = 0)'
 * 			       ;
 *     		$this->_db->setQuery($query, 0, 1);
 *     		$this->totalsaway = $this->_db->loadObject();
 *     	}
 *     	return $this->totalsaway;
 *     }
 */

    
		/**
		 * sportsmanagementModelTeamStats::getChartData()
		 * 
		 * @return
		 */
		function getChartData( )
		{
		  $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('rounds.id');
        $query->select('SUM(CASE WHEN st1.team_id ='.$this->teamid.' THEN matches.team1_result ELSE matches.team2_result END) AS goalsfor');
        $query->select('SUM(CASE WHEN st1.team_id ='.$this->teamid.' THEN matches.team2_result ELSE matches.team1_result END) AS goalsagainst');
        $query->select('rounds.roundcode');
        
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_round AS rounds ');
        
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS matches ON rounds.id = matches.round_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = matches.projectteam1_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt2 ON pt2.id = matches.projectteam2_id  ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st2 ON st2.id = pt2.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t2 ON st2.team_id = t2.id ');
        
        $query->where('rounds.project_id = '.$this->projectid);
        $query->where('( (st1.team_id ='.$this->teamid.' ) OR (st2.team_id ='.$this->teamid.' ) )' );
        $query->where('(matches.cancel IS NULL OR matches.cancel = 0)');
        $query->where('team1_result IS NOT NULL');
        $query->group('rounds.roundcode');   


/*        
			$query = ' SELECT rounds.id, '
			       . ' SUM(CASE WHEN pt1.team_id ='.$this->teamid.' THEN matches.team1_result ELSE matches.team2_result END) AS goalsfor, '
			       . ' SUM(CASE WHEN pt1.team_id ='.$this->teamid.' THEN matches.team2_result ELSE matches.team1_result END) AS goalsagainst, '
			       . ' rounds.roundcode '
			       . ' FROM #__joomleague_round AS rounds '
			       . ' INNER JOIN #__joomleague_match AS matches ON rounds.id = matches.round_id '
			       . ' INNER JOIN #__joomleague_project_team AS pt1 ON pt1.id = matches.projectteam1_id '
			       . ' INNER JOIN #__joomleague_project_team AS pt2 ON pt2.id = matches.projectteam2_id '
			       . ' WHERE rounds.project_id = '.$this->projectid
			       . '   AND ((pt1.team_id ='.$this->teamid.' ) '
			       . '     OR (pt2.team_id ='.$this->teamid.' )) '
				   . '   AND (matches.cancel IS NULL OR matches.cancel = 0)'
//			       . '   AND team1_result IS NOT NULL '
			       . ' GROUP BY rounds.roundcode'
			       ;
*/                   
                   
                   
    		$db->setQuery( $query );
    		$this->matchdaytotals = $db->loadObjectList();
            
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
            if ( !$this->matchdaytotals )
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
            
            
            
    		return $this->matchdaytotals;
    }
    
    /**
     * sportsmanagementModelTeamStats::getMatchDayTotals()
     * 
     * @return
     */
    function getMatchDayTotals( )
    {
        $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
    	if ( is_null( $this->matchdaytotals ) )
    	{
    	   $query->select('rounds.id');
           $query->select('COUNT(matches.round_id) AS totalmatchespd');
           $query->select('COUNT(matches.id) as playedmatchespd');
           $query->select('SUM(matches.team1_result) AS homegoalspd');
           $query->select('SUM(matches.team2_result) AS guestgoalspd');
           $query->select('rounds.roundcode');

           $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_round AS rounds ');
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS matches ON rounds.id = matches.round_id ');
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = matches.projectteam1_id ');
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt2 ON pt2.id = matches.projectteam2_id  ');
           
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
           
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st2 ON st2.id = pt2.team_id ');
           $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t2 ON st2.team_id = t2.id ');
           
           $query->where('rounds.project_id = '.$this->projectid);
           $query->where('( (st1.team_id ='.$this->teamid.' ) OR (st2.team_id ='.$this->teamid.' ) )' );
           $query->where('(matches.cancel IS NULL OR matches.cancel = 0)');
           $query->group('rounds.roundcode');
        


/*    	   
    		$query = ' SELECT rounds.id, '
			       . ' COUNT(matches.round_id) AS totalmatchespd, '
			       . ' COUNT(matches.id) as playedmatchespd, '
			       . ' SUM(matches.team1_result) AS homegoalspd, '
			       . ' SUM(matches.team2_result) AS guestgoalspd, '
			       . ' rounds.roundcode '
			       . ' FROM #__joomleague_round AS rounds '
			       . ' INNER JOIN #__joomleague_match AS matches ON rounds.id = matches.round_id '
			       . ' INNER JOIN #__joomleague_project_team AS pt1 ON pt1.id = matches.projectteam1_id '
			       . ' INNER JOIN #__joomleague_project_team AS pt2 ON pt2.id = matches.projectteam2_id '
			       . ' WHERE rounds.project_id = '.$this->projectid
			       . '   AND ((pt1.team_id ='.$this->teamid.' ) '
			       . '     OR (pt2.team_id ='.$this->teamid.' )) '
			       . ' GROUP BY rounds.roundcode'
				   . '   AND (matches.cancel IS NULL OR matches.cancel = 0)'
			       ;
*/                   
    		$db->setQuery( $query );
            
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
    		$this->matchdaytotals = $db->loadObjectList();
            
            if ( !$this->matchdaytotals )
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
            
            
            
    	}
    	return $this->matchdaytotals;
    }

    /**
     * sportsmanagementModelTeamStats::getTotalRounds()
     * 
     * @return
     */
    function getTotalRounds( )
    {
        $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        if ( is_null( $this->totalrounds ) )
        {
            $query->select('COUNT(id)');
           $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_round ');
           $query->where('project_id = '.$this->projectid);

//            $query= "SELECT COUNT(id)
//                     FROM #__joomleague_round
//                     WHERE project_id= ".$this->projectid;
                     
            $db->setQuery($query);
            $this->totalrounds = $db->loadResult();
        }
        
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        if ( !$this->totalrounds )
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
        
        return $this->totalrounds;
    }

   
    /**
     * sportsmanagementModelTeamStats::_getAttendance()
     * 
     * @return
     */
    function _getAttendance( )
    {
        $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
    	if ( is_null( $this->attendanceranking ) )
    	{
    	   $query->select('matches.crowd');

        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS matches ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = matches.projectteam1_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
        $query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_playground AS playground ON pt1.standard_playground = playground.id ');
        
        $query->where('st1.team_id = '.$this->teamid);
        $query->where('matches.crowd > 0 ');
        $query->where('matches.published = 1');

/*
				$query = ' SELECT matches.crowd '
				       . ' FROM #__joomleague_match AS matches '
				       . ' INNER JOIN #__joomleague_project_team pt1 ON pt1.id = matches.projectteam1_id '
				       . ' INNER JOIN #__joomleague_team t1 ON t1.id = pt1.team_id '
				       . ' LEFT JOIN #__joomleague_playground AS playground ON pt1.standard_playground = playground.id '
				       . ' WHERE pt1.team_id = '.$this->teamid
				       . '   AND matches.crowd > 0 '
				       . '   AND matches.published=1 '
				       ;
*/                       
    		$db->setQuery( $query );
    		$this->attendanceranking = $db->loadResultArray();
    	}
        
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
    	         
        if ( !$this->attendanceranking )
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
        
    	return $this->attendanceranking;
    }

	/**
	 * sportsmanagementModelTeamStats::getBestAttendance()
	 * 
	 * @return
	 */
	function getBestAttendance( )
	{
		$attendance = self::_getAttendance();
		return (count($attendance)>0) ? max($attendance) : 0;
	}

	/**
	 * sportsmanagementModelTeamStats::getWorstAttendance()
	 * 
	 * @return
	 */
	function getWorstAttendance( )
	{
		$attendance = self::_getAttendance();
		return (count($attendance)>0) ? min($attendance) : 0;
	}

	/**
	 * sportsmanagementModelTeamStats::getTotalAttendance()
	 * 
	 * @return
	 */
	function getTotalAttendance( )
	{
		$attendance = self::_getAttendance();
		return (count($attendance)>0) ? array_sum($attendance) : 0;
	}
	
	/**
	 * sportsmanagementModelTeamStats::getAverageAttendance()
	 * 
	 * @return
	 */
	function getAverageAttendance( )
	{
		$attendance = self::_getAttendance();
		return (count($attendance)>0) ? round(array_sum($attendance)/count($attendance), 0) : 0;
	}

	/**
	 * sportsmanagementModelTeamStats::getChartURL()
	 * 
	 * @return
	 */
	function getChartURL( )
	{
		$url = sportsmanagementHelperRoute::getTeamStatsChartDataRoute( $this->projectid, $this->teamid );
		$url = str_replace( '&', '%26', $url );
		return $url;
	}

	/**
	 * sportsmanagementModelTeamStats::getLogo()
	 * 
	 * @return
	 */
	function getLogo( )
	{
		$database = JFactory::getDBO();
	    $query = "SELECT logo_big
				FROM #__joomleague_club clubs
				LEFT JOIN #__joomleague_team teams ON clubs.id = teams.club_id
				WHERE teams.id = ".$this->teamid;

    	$database->setQuery( $query );
    	$logo = JURI::root().$database->loadResult();

		return $logo;
	}

	/**
	 * sportsmanagementModelTeamStats::getResults()
	 * 
	 * @return
	 */
	function getResults()
	{
	   $option = JRequest::getCmd('option');
	    $mainframe = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('m.id, m.projectteam1_id, m.projectteam2_id, pt1.team_id AS team1_id, pt2.team_id AS team2_id');
        $query->select('m.team1_result, m.team2_result');
        $query->select('m.alt_decision, m.team1_result_decision, m.team2_result_decision');


        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS m ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt1 ON pt1.id = m.projectteam1_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt2 ON pt2.id = m.projectteam2_id  ');
           
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st1 ON st1.id = pt1.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t1 ON st1.team_id = t1.id ');
         
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st2 ON st2.id = pt2.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t2 ON st2.team_id = t2.id ');
           
        $query->where('pt1.project_id = '.$this->projectid);
        $query->where('( (st1.team_id ='.$this->teamid.' ) OR (st2.team_id ='.$this->teamid.' ) )' );
           
        $query->where('(m.team1_result IS NOT NULL OR m.alt_decision > 0)');
        $query->where('(m.cancel IS NULL OR m.cancel = 0)');
           
        
/*        
		$query = ' SELECT m.id, m.projectteam1_id, m.projectteam2_id, pt1.team_id AS team1_id, pt2.team_id AS team2_id, '
		       . ' m.team1_result, m.team2_result, '
		       . ' m.alt_decision, m.team1_result_decision, m.team2_result_decision '
		       . ' FROM #__joomleague_match AS m '
		       . ' INNER JOIN #__joomleague_project_team AS pt1 ON pt1.id = m.projectteam1_id '
		       . ' INNER JOIN #__joomleague_project_team AS pt2 ON pt2.id = m.projectteam2_id '
		       . ' WHERE m.published = 1 '
		       . '   AND pt1.project_id = '. $this->_db->Quote($this->projectid)
		       . '   AND (pt1.team_id = '. $this->_db->Quote($this->teamid) . ' OR pt2.team_id = '. $this->_db->Quote($this->teamid) . ')'
		       . '   AND (m.team1_result IS NOT NULL OR m.alt_decision > 0)'
			   . '   AND (m.cancel IS NULL OR m.cancel = 0)'
		       ;
*/

		$db->setQuery($query);
        $matches = $db->loadObjectList();
        
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
    	         
        if ( !$matches )
        {
            $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }
		
		$results = array(	'win' => array(), 'tie' => array(), 'loss' => array(), 'forfeit' => array(),
							'home_wins' => 0, 'home_draws' => 0, 'home_losses' => 0, 
							'away_wins' => 0, 'away_draws' => 0, 'away_losses' => 0,);
		foreach ($matches as $match)
		{
			if (!$match->alt_decision)
			{
				if ($match->team1_id == $this->teamid)
				{
					// We are the home team
					if ($match->team1_result > $match->team2_result)
					{
						$results['win'][] = $match;
						$results['home_wins']++;
					}
					else if ($match->team1_result < $match->team2_result)
					{
						$results['loss'][] = $match;
						$results['home_losses']++;
					}
					else
					{
						$results['tie'][] = $match;
						$results['home_draws']++;
					}
				}
				else
				{
					// We are the away team
					if ($match->team1_result > $match->team2_result)
					{
						$results['loss'][] = $match;
						$results['away_losses']++;
					}
					else if ($match->team1_result < $match->team2_result)
					{
						$results['win'][] = $match;
						$results['away_wins']++;
					}
					else
					{
						$results['tie'][] = $match;
						$results['away_draws']++;
					}
				}
			}
			else
			{
				if ($match->team1_id == $this->teamid)
				{
					// We are the home team
					if (empty($match->team1_result_decision)) {
						$results['forfeit'][] = $match;
					}
					else if (empty($match->team2_result_decision)) {
						$results['win'][] = $match;
					}
					else {
						if ($match->team1_result_decision > $match->team2_result_decision) {
							$results['win'][] = $match;
							$results['home_wins']++;
						}
						else if ($match->team1_result_decision < $match->team2_result_decision) {
							$results['loss'][] = $match;
							$results['home_losses']++;
						}
						else {
							$results['tie'][] = $match;
							$results['home_draws']++;
						}
					}
				}
				else
				{
					// We are the away team
					if (empty($match->team2_result_decision)) {
						$results['forfeit'][] = $match;
					}
					else if (empty($match->team1_result_decision)) {
						$results['win'][] = $match;
					}
					else {
						if ($match->team1_result_decision > $match->team2_result_decision) {
							$results['loss'][] = $match;
							$results['away_losses']++;
						}
						else if ($match->team1_result_decision < $match->team2_result_decision) {
							$results['win'][] = $match;
							$results['away_wins']++;
						}
						else {
							$results['tie'][] = $match;
							$results['away_draws']++;
						}
					}
				}
			}
		}
		
		return $results;
	}
	
	/**
	 * sportsmanagementModelTeamStats::getStats()
	 * 
	 * @return
	 */
	function getStats()
	{
		$stats = sportsmanagementModelProject::getProjectStats();
		
		// those are per positions, group them so that we have team globlas stats
		
		$teamstats = array();
		foreach ($stats as $pos => $pos_stats)
		{
			foreach ($pos_stats as $k => $stat) 
			{
				if ($stat->getParam('show_in_teamstats', 1))
				{
					if (!isset($teamstats[$k])) 
					{
						$teamstats[$k] = $stat;
						$teamstats[$k]->value = $stat->getRosterTotalStats($this->teamid, $this->projectid);
					}
				}
			}
		}
		
		return $teamstats;
	}
}
?>