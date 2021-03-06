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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'statistics'.DS.'base.php');


/**
 * SMStatisticPercentage
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class SMStatisticPercentage extends SMStatistic 
{
//also the name of the associated xml file	
	var $_name = 'percentage';
	
	var $_calculated = 1;
	
	var $_showinsinglematchreports = 1;

	var $_percentageSymbol = null;
	
	/**
	 * SMStatisticPercentage::__construct()
	 * 
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * SMStatisticPercentage::getSids()
	 * 
	 * @return
	 */
	function getSids($id_field = 'numerator_ids')
	{
	   $app = JFactory::getApplication();
		$params = SMStatistic::getParams();
		
        //$numerator_ids = explode(',', $params->get('numerator_ids'));
        $numerator_ids = $params->get('numerator_ids');
		if (!count($numerator_ids)) 
        {
			JError::raiseWarning(0, JText::sprintf('STAT %s/%s WRONG CONFIGURATION', $this->_name, $this->id));
			return(array(0));
		}
		//$denominator_ids = explode(',', $params->get('denominator_ids'));
        $denominator_ids = $params->get('denominator_ids');
		if (!count($denominator_ids)) 
        {
			JError::raiseWarning(0, JText::sprintf('STAT %s/%s WRONG CONFIGURATION', $this->_name, $this->id));
			return(array(0));
		}
				
		$db = JFactory::getDBO();
		$ids = array('num' => array(), 'den' => array());
		foreach ($numerator_ids as $s) 
        {
			$ids['num'][] = (int)$s;
		}		
		foreach ($denominator_ids as $s) 
        {
			$ids['den'][] = (int)$s;
		}
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' ids<br><pre>'.print_r($ids,true).'</pre>'),'');
        }
        
		return $ids;
	}

	/**
	 * SMStatisticPercentage::getQuotedSids()
	 * 
	 * @return
	 */
	function getQuotedSids($id_field = 'numerator_ids')
	{
		$app = JFactory::getApplication();
        $params = SMStatistic::getParams();
		//$numerator_ids = explode(',', $params->get('numerator_ids'));
        $numerator_ids = $params->get('numerator_ids');
		if (!count($numerator_ids)) 
        {
			JError::raiseWarning(0, JText::sprintf('STAT %s/%s WRONG CONFIGURATION', $this->_name, $this->id));
			return(array(0));
		}
		//$denominator_ids = explode(',', $params->get('denominator_ids'));
        $denominator_ids = $params->get('denominator_ids');
		if (!count($denominator_ids)) 
        {
			JError::raiseWarning(0, JText::sprintf('STAT %s/%s WRONG CONFIGURATION', $this->_name, $this->id));
			return(array(0));
		}
				
		$db = JFactory::getDBO();
		$ids = array('num' => array(), 'den' => array());
		foreach ($numerator_ids as $s) 
        {
			$ids['num'][] = $db->Quote((int)$s);
		}		
		foreach ($denominator_ids as $s) 
        {
			$ids['den'][] = $db->Quote((int)$s);
		}
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' ids<br><pre>'.print_r($ids,true).'</pre>'),'');
        }
        
		return $ids;
	}
	
	function getMatchPlayerStat(&$gamemodel, $teamplayer_id)
	{
		$gamestats = $gamemodel->getPlayersStats();
		$ids = self::getSids();
		
		$num = 0;
		foreach ($ids['num'] as $id) 
		{
			if (isset($gamestats[$teamplayer_id][$id])) {
				$num += $gamestats[$teamplayer_id][$id];
			}
		}
		$den = 0;
		foreach ($ids['den'] as $id) 
		{
			if (isset($gamestats[$teamplayer_id][$id])) {
				$den += $gamestats[$teamplayer_id][$id];
			}
		}
		return $this->formatValue($num, $den, $this->getPrecision(), $this->getShowPercentageSymbol());
	}

	function getPlayerStatsByGame($teamplayer_ids, $project_id)
	{
		$sids = self::getSids();
		$num = $this->getPlayerStatsByGameForIds($teamplayer_ids, $project_id, $sids['num']);
		$den = $this->getPlayerStatsByGameForIds($teamplayer_ids, $project_id, $sids['den']);
		$precision = $this->getPrecision();
		$showPercentageSymbol = $this->getShowPercentageSymbol();

		$res = array();
		foreach (array_unique(array_merge(array_keys($num), array_keys($den))) as $match_id) 
		{
			$res[$match_id] = new stdclass();
			$res[$match_id]->match_id = $match_id;
			$n = isset($num[$match_id]->value) ? $num[$match_id]->value : 0;
			$d = isset($den[$match_id]->value) ? $den[$match_id]->value : 0;
			$res[$match_id]->value = $this->formatValue($n, $d, $precision, $showPercentageSymbol);
		}
		return $res;
	}
	
	function getPlayerStatsByProject($person_id, $projectteam_id = 0, $project_id = 0, $sports_type_id = 0)
	{
		$sids = self::getSids();

		$num = $this->getPlayerStatsByProjectForIds($person_id, $projectteam_id, $project_id, $sports_type_id, $sids['num']);
		$den = $this->getPlayerStatsByProjectForIds($person_id, $projectteam_id, $project_id, $sports_type_id, $sids['den']);

		return $this->formatValue($num, $den, $this->getPrecision(), $this->getShowPercentageSymbol());
	}

	/**
	 * Get players stats
	 * @param $team_id
	 * @param $project_id
	 * @return array
	 */
	function getRosterStats($team_id, $project_id, $position_id)
	{
		$sids = self::getSids();
		$num = SMStatistic::getRosterStatsForIds($team_id, $project_id, $position_id, $sids['num']);
		$den = SMStatistic::getRosterStatsForIds($team_id, $project_id, $position_id, $sids['den']);
		$precision = SMStatistic::getPrecision();
		$showPercentageSymbol = self::getShowPercentageSymbol();

		$res = array();
		foreach (array_unique(array_merge(array_keys($num), array_keys($den))) as $person_id) 
		{
			$res[$person_id] = new stdclass();
			$res[$person_id]->person_id = $person_id;
			$n = isset($num[$person_id]->value) ? $num[$person_id]->value : 0;
			$d = isset($den[$person_id]->value) ? $den[$person_id]->value : 0;
			$res[$person_id]->value = self::formatValue($n, $d, $precision, $showPercentageSymbol);
		}
		return $res;
	}

	/**
	 * SMStatisticPercentage::getPlayersRanking()
	 * 
	 * @param mixed $project_id
	 * @param mixed $division_id
	 * @param mixed $team_id
	 * @param integer $limit
	 * @param integer $limitstart
	 * @param mixed $order
	 * @return
	 */
	function getPlayersRanking($project_id, $division_id, $team_id, $limit = 20, $limitstart = 0, $order=null)
	{
		$sids = self::getQuotedSids();
		
		$option = JRequest::getCmd('option');
	$app = JFactory::getApplication();
		$db = JFactory::getDBO();
        
        $query_num = JFactory::getDbo()->getQuery(true);
        $query_den = JFactory::getDbo()->getQuery(true);
        $query_core = JFactory::getDbo()->getQuery(true);
        
        $query_num->select('SUM(ms.value) AS num, tp.id AS tpid, tp.person_id');
        $query_num->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_person_id AS tp');
        $query_num->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id AS st ON st.team_id = tp.team_id ');
        $query_num->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt ON pt.team_id = st.id');
        $query_num->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_match_statistic AS ms ON ms.teamplayer_id = tp.id AND ms.statistic_id IN ('. implode(',', $sids['num']) .')');
        $query_num->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS m ON m.id = ms.match_id AND m.published = 1 ');
        $query_num->where('pt.project_id = ' . $project_id);
        
//		$query_num	= ' SELECT SUM(ms.value) AS num, tp.id AS tpid'
//					. ' FROM #__joomleague_team_player AS tp '
//					. ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
//					. ' INNER JOIN #__joomleague_match_statistic AS ms ON ms.teamplayer_id = tp.id '
//					. '   AND ms.statistic_id IN ('. implode(',', $sids['num']) .')'
//					. ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
//					. '   AND m.published = 1 '
//					. ' WHERE pt.project_id = '. $db->Quote($project_id);
                    
		if ($division_id != 0)
		{
			//$query_num .= ' AND pt.division_id = '. $db->Quote($division_id);
            $query_num->where('pt.division_id = ' . $division_id);
		}
		if ($team_id != 0)
		{
			//$query_num .= '   AND pt.team_id = ' . $db->Quote($team_id);
            $query_num->where('st.team_id = ' . $team_id);
		}
		//$query_num .= ' GROUP BY tp.id ';
        $query_num->group('tp.id');
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query_num<br><pre>'.print_r($query_num->dump(),true).'</pre>'),''); 
		
        $query_den->select('SUM(ms.value) AS den, tp.id AS tpid, tp.person_id');
        $query_den->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_person_id AS tp');
        $query_den->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id AS st ON st.team_id = tp.team_id ');
        $query_den->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt ON pt.team_id = st.id');
        $query_den->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_match_statistic AS ms ON ms.teamplayer_id = tp.id AND ms.statistic_id IN ('. implode(',', $sids['den']) .')');
        $query_den->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS m ON m.id = ms.match_id AND m.published = 1 ');
        $query_den->where('pt.project_id = ' . $project_id);
        
//		$query_den = ' SELECT SUM(ms.value) AS den, tp.id AS tpid'
//			. ' FROM #__joomleague_team_player AS tp '
//			. ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
//			. ' INNER JOIN #__joomleague_match_statistic AS ms ON ms.teamplayer_id = tp.id '
//			. '   AND ms.statistic_id IN ('. implode(',', $sids['den']) .')'
//			. ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
//			. '   AND m.published = 1 '
//			. ' WHERE pt.project_id = '. $db->Quote($project_id)
//		;

		if ($division_id != 0)
		{
			//$query_den .= ' AND pt.division_id = '. $db->Quote($division_id);
            $query_den->where('pt.division_id = ' . $division_id);
		}
		if ($team_id != 0)
		{
			//$query_den .= '   AND pt.team_id = ' . $db->Quote($team_id);
            $query_den->where('st.team_id = ' . $team_id);
		}
//		$query_den .= '   AND value > 0 '
//			. ' GROUP BY tp.id '
//		;
		
        $query_den->where('ms.value > 0');
        $query_den->group('tp.id');
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query_den<br><pre>'.print_r($query_den->dump(),true).'</pre>'),'');
        
		$query_select_count = 'COUNT(DISTINCT tp.id) as count';
//		$query_select_details	= '(n.num / d.den) AS total, 1 as rank,'
//								. ' tp.id AS teamplayer_id, tp.person_id, tp.picture AS teamplayerpic,'
//								. ' p.firstname, p.nickname, p.lastname, p.picture, p.country,'
//								. ' pt.team_id, pt.picture AS projectteam_picture,'
//								. ' t.picture AS team_picture, t.name AS team_name, t.short_name AS team_short_name';
 		$query_select_details	= '(n.num / d.den) AS total, 1 as rank,'
								. ' tp.id AS teamplayer_id, tp.person_id, tp.picture AS teamplayerpic,'
								. ' p.firstname, p.nickname, p.lastname, p.picture, p.country,'
								. ' st.team_id, pt.picture AS projectteam_picture,'
								. ' t.picture AS team_picture, t.name AS team_name, t.short_name AS team_short_name';
                                
        $query_core->select($query_select_count);
        $query_core->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_person_id AS tp');
        $query_core->join('INNER','('.$query_num.') AS n ON n.tpid = tp.id');
        $query_core->join('INNER','('.$query_den.') AS d ON d.tpid = tp.id');
        $query_core->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_person AS p ON p.id = tp.person_id ');
        $query_core->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id AS st ON st.team_id = tp.team_id ');
        $query_core->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt ON pt.team_id = st.id');
        $query_core->join('INNER','#__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t ON st.team_id = t.id');
        $query_core->where('pt.project_id = ' . $project_id);
        $query_core->where('p.published = 1');
        
//        $query_core	= ' FROM #__joomleague_team_player AS tp'
//					. ' INNER JOIN ('.$query_num.') AS n ON n.tpid = tp.id'
//					. ' INNER JOIN ('.$query_den.') AS d ON d.tpid = tp.id'
//					. ' INNER JOIN #__joomleague_person AS p ON p.id = tp.person_id'
//					. ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id'
//					. ' INNER JOIN #__joomleague_team AS t ON pt.team_id = t.id'
//					. ' WHERE pt.project_id = '. $db->Quote($project_id)
//				    . '   AND p.published = 1 '
//		;

		if ($division_id != 0)
		{
			//$query_core .= ' AND pt.division_id = '. $db->Quote($division_id);
            $query_core->where('pt.division_id = ' . $division_id);
		}
		if ($team_id != 0)
		{
			//$query_core .= '   AND pt.team_id = ' . $db->Quote($team_id);
            $query_core->where('st.team_id = ' . $team_id);
		}
		
        //$query_end_details = ' ORDER BY total '.(!empty($order) ? $order : $this->getParam('ranking_order', 'DESC')).' ';

		$res = new stdclass;
		$db->setQuery($query_core);
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query_core<br><pre>'.print_r($query_core->dump(),true).'</pre>'),'');
        
		$res->pagination_total = $db->loadResult();
        
        $query_core->clear('select');
        $query_core->select($query_select_details);
		$query_core->order('total '.(!empty($order) ? $order : $this->getParam('ranking_order', 'DESC')).' ');

		$db->setQuery($query_core, $limitstart, $limit);
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query_core<br><pre>'.print_r($query_core->dump(),true).'</pre>'),'');
        
		$res->ranking = $db->loadObjectList();
	
		if ($res->ranking)
		{
			$precision = $this->getPrecision();
			$showPercentageSymbol = $this->getShowPercentageSymbol();

			// get ranks
			$previousval = 0;
			$currentrank = 1 + $limitstart;
			foreach ($res->ranking as $k => $row) 
			{
				if ($row->total == $previousval) {
					$res->ranking[$k]->rank = $currentrank;
				}
				else {
					$res->ranking[$k]->rank = $k + 1 + $limitstart;
				}
				$previousval = $row->total;
				$currentrank = $res->ranking[$k]->rank;

				$res->ranking[$k]->total = $this->formatValue($res->ranking[$k]->total, 1, $precision, $showPercentageSymbol);
			}
		}

		return $res;
	}
		
	/**
	 * SMStatisticPercentage::getTeamsRanking()
	 * 
	 * @param mixed $project_id
	 * @param integer $limit
	 * @param integer $limitstart
	 * @param mixed $order
	 * @return
	 */
	function getTeamsRanking($project_id, $limit = 20, $limitstart = 0, $order=null)
	{
		$sids = self::getQuotedSids();
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
        
        $query_num = SMStatistic::getTeamsRankingStatisticNumQuery($project_id, $sids['num']);
//		$query_num = ' SELECT SUM(ms.value) AS num, pt.id '
//		       . ' FROM #__joomleague_team_player AS tp '
//		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
//		       . ' INNER JOIN #__joomleague_match_statistic AS ms ON ms.teamplayer_id = tp.id '
//		       . '   AND ms.statistic_id IN ('. implode(',', $sids['num']) .')'
//		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
//		       . '   AND m.published = 1 '
//		       . ' WHERE pt.project_id = '. $db->Quote($project_id)
//		       . ' GROUP BY pt.id '
//		       ;
		
        $query_den = SMStatistic::getTeamsRankingStatisticDenQuery($project_id, $sids['den']);
//		$query_den = ' SELECT SUM(ms.value) AS den, pt.id '
//		       . ' FROM #__joomleague_team_player AS tp '
//		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
//		       . ' INNER JOIN #__joomleague_match_statistic AS ms ON ms.teamplayer_id = tp.id '
//		       . '   AND ms.statistic_id IN ('. implode(',', $sids['den']) .')'
//		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
//		       . '   AND m.published = 1 '
//		       . ' WHERE pt.project_id = '. $db->Quote($project_id)
//		       . '   AND value > 0 '
//		       . ' GROUP BY pt.id '
//		       ;

		$query = SMStatistic::getTeamsRankingStatisticCoreQuery($project_id, $query_num, $query_den);
//        $query = ' SELECT (n.num / d.den) AS total, pt.team_id ' 
//		       . ' FROM #__joomleague_project_team AS pt '
//		       . ' INNER JOIN ('.$query_num.') AS n ON n.id = pt.id '
//		       . ' INNER JOIN ('.$query_den.') AS d ON d.id = pt.id '
//		       . ' INNER JOIN #__joomleague_team AS t ON pt.team_id = t.id '
//		       . ' WHERE pt.project_id = '. $db->Quote($project_id)
//		       . ' ORDER BY total '.(!empty($order) ? $order : $this->getParam('ranking_order', 'DESC')).' '
//		       ;
		
        $query->group((!empty($order) ? $order : $this->getParam('ranking_order', 'DESC')).' ');
        
		$db->setQuery($query, $limitstart, $limit);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        }
        
		$res = $db->loadObjectList();

		if (!empty($res))
		{
			$precision = $this->getPrecision();
			$showPercentageSymbol = self::getShowPercentageSymbol();

			// get ranks
			$previousval = 0;
			$currentrank = 1 + $limitstart;
			foreach ($res as $k => $row) 
			{
				if ($row->total == $previousval) {
					$res[$k]->rank = $currentrank;
				}
				else {
					$res[$k]->rank = $k + 1 + $limitstart;
				}
				$previousval = $row->total;
				$currentrank = $res[$k]->rank;

				$res[$k]->total = $this->formatValue($res[$k]->total, 1, $precision, $showPercentageSymbol);
			}
		}
		return $res;
	}

	function getMatchStaffStat(&$gamemodel, $team_staff_id)
	{
		$gamestats = $gamemodel->getMatchStaffStats();
		$ids = self::getSids();
		
		$num = 0;
		foreach ($ids['num'] as $id) 
		{
			if (isset($gamestats[$team_staff_id][$id])) {
				$num += $gamestats[$team_staff_id][$id];
			}
		}
		$den = 0;
		foreach ($ids['den'] as $id) 
		{
			if (isset($gamestats[$team_staff_id][$id])) {
				$den += $gamestats[$team_staff_id][$id];
			}
		}
		return $this->formatValue($num, $den, $this->getPrecision(), $this->getShowPercentageSymbol());
	}
	
	function getStaffStats($person_id, $team_id, $project_id)
	{
		$sids = $this->getQuotedSids();
		
		$db = &JFactory::getDBO();
		$query = ' SELECT SUM(ms.value) AS value, tp.person_id '
		       . ' FROM #__joomleague_team_staff AS tp '
		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
		       . ' INNER JOIN #__joomleague_match_staff_statistic AS ms ON ms.team_staff_id = tp.id '
		       . '   AND ms.statistic_id IN ('. implode(',', $sids['num']) .')'
		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
		       . '   AND m.published = 1 '
		       . ' WHERE pt.team_id = '. $db->Quote($team_id)
		       . '   AND pt.project_id = '. $db->Quote($project_id)
		       . '   AND tp.person_id = '. $db->Quote($person_id)
		       . ' GROUP BY tp.id '
		       ;
		$db->setQuery($query);
		$num = $db->loadResult();
		
		$query = ' SELECT SUM(ms.value) AS value, tp.person_id '
		       . ' FROM #__joomleague_team_staff AS tp '
		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
		       . ' INNER JOIN #__joomleague_match_staff_statistic AS ms ON ms.team_staff_id = tp.id '
		       . '   AND ms.statistic_id IN ('. implode(',', $sids['den']) .')'
		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
		       . '   AND m.published = 1 '
		       . ' WHERE pt.team_id = '. $db->Quote($team_id)
		       . '   AND pt.project_id = '. $db->Quote($project_id)
		       . '   AND value > 0 '
		       . '   AND tp.person_id = '. $db->Quote($person_id)
		       . ' GROUP BY tp.id '
		       ;
		$db->setQuery($query);
		$den = $db->loadResult();
	
		return $this->formatValue($num, $den, $this->getPrecision(), $this->getShowPercentageSymbol());
	}
	

	function getHistoryStaffStats($person_id)
	{
		$sids = $this->getQuotedSids();
		
		$db = &JFactory::getDBO();
		$query = ' SELECT SUM(ms.value) AS value, tp.person_id '
		       . ' FROM #__joomleague_team_staff AS tp '
		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
		       . ' INNER JOIN #__joomleague_match_staff_statistic AS ms ON ms.team_staff_id = tp.id '
		       . '   AND ms.statistic_id IN ('. implode(',', $sids['num']) .')'
		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
		       . '   AND m.published = 1 '
		       . ' WHERE tp.person_id = '. $db->Quote($person_id)
		       . ' GROUP BY tp.id '
		       ;
		$db->setQuery($query);
		$num = $db->loadResult();
		
		$query = ' SELECT SUM(ms.value) AS value, tp.person_id '
		       . ' FROM #__joomleague_team_staff AS tp '
		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
		       . ' INNER JOIN #__joomleague_match_staff_statistic AS ms ON ms.team_staff_id = tp.id '
		       . '   AND ms.statistic_id IN ('. implode(',', $sids['den']) .')'
		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
		       . '   AND m.published = 1 '
		       . ' WHERE value > 0 '
		       . '   AND tp.person_id = '. $db->Quote($person_id)
		       . ' GROUP BY tp.id '
		       ;
		$db->setQuery($query);
		$den = $db->loadResult();
	
		return self::formatValue($num, $den, $this->getPrecision(), $this->getShowPercentageSymbol());
	}

	/**
	 * SMStatisticPercentage::getShowPercentageSymbol()
	 * 
	 * @return
	 */
	function getShowPercentageSymbol()
	{
		$params = SMStatistic::getParams();
		return $params->get('show_percent_symbol', 1);
	}

	/**
	 * SMStatisticPercentage::formatValue()
	 * 
	 * @param mixed $num
	 * @param mixed $den
	 * @param mixed $precision
	 * @param mixed $showPercentageSymbol
	 * @return
	 */
	function formatValue($num, $den, $precision, $showPercentageSymbol)
	{
		$value = (!empty($num) && !empty($den)) ? $num / $den : 0;
		if ($showPercentageSymbol)
		{
			$formattedValue = number_format(100 * $value, $precision) . "%";
		}
		else
		{
			$formattedValue = number_format($value, $precision);
		}
		return $formattedValue;
	}

	/**
	 * SMStatisticPercentage::formatZeroValue()
	 * 
	 * @return
	 */
	function formatZeroValue()
	{
		return self::formatValue(0, 0, $this->getPrecision(), $this->getShowPercentageSymbol());
	}
}
