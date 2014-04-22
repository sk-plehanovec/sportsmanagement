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

defined('_JEXEC') or die('Restricted access');

/**
 * JFormFieldpredictionmatchid
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class JFormFieldpredictionmatchid extends JFormField
{

	var	$_name = 'predictionmatchid';

	/**
	 * JFormFieldpredictionmatchid::getInput()
	 * 
	 * @return
	 */
	protected function getInput()
  {
		$db = JFactory::getDBO();
    $mainframe = JFactory::getApplication();
		$option	= 'com_sportsmanagement';
		//$prediction_id = (int) $mainframe->getUserState( $option . 'prediction_id' );
        $prediction_id = $mainframe->getUserState( "$option.predid", '0' );
        
        // welche tabelle soll genutzt werden
        $params = JComponentHelper::getParams( 'com_sportsmanagement' );
        $database_table	= $params->get( 'cfg_which_database_table' );

//$mainframe->enqueueMessage(JText::_('prediction_id -> <pre> '.print_r($prediction_id,true).'</pre><br>' ),'Notice');		

    $query	= $db->getQuery(true);
    $query->select('m.id AS id,m.match_date');
    $query->select('r.roundcode,r.name as roundname');
    $query->select('t1.name as home');
    $query->select('t2.name as away');
    $query->from('#__'.$database_table.'_match AS m');
    $query->join('INNER', '#__'.$database_table.'_round AS r ON r.id = m.round_id');
    $query->join('INNER', '#__'.$database_table.'_prediction_project as prepro on prepro.project_id = r.project_id');
    $query->join('LEFT', '#__'.$database_table.'_project_team AS tt1 ON m.projectteam1_id = tt1.id');
    $query->join('LEFT', '#__'.$database_table.'_project_team AS tt2 ON m.projectteam2_id = tt2.id');
    
    $query->join('LEFT','#__'.$database_table.'_season_team_id AS st1 ON st1.id = tt1.team_id ');
    $query->join('LEFT','#__'.$database_table.'_season_team_id AS st2 ON st2.id = tt2.team_id ');
        
    $query->join('LEFT', '#__'.$database_table.'_team AS t1 ON t1.id = st1.team_id');
    $query->join('LEFT', '#__'.$database_table.'_team AS t2 ON t2.id = st2.team_id');
    
    $query->where('prepro.prediction_id = '. $prediction_id);
    
//    $query=	"	SELECT	m.id AS id,
//								m.match_date,
//								r.roundcode,
//								r.name as roundname,
//								t1.name as home,
//								t2.name as away
//
//						FROM #__sportsmanagement_match AS m
//						INNER JOIN #__sportsmanagement_round AS r 
//            ON r.id = m.round_id
//            inner join #__sportsmanagement_prediction_project as prepro
//            on prepro.project_id = r.project_id
//            LEFT JOIN #__sportsmanagement_project_team AS tt1 ON m.projectteam1_id=tt1.id
//			LEFT JOIN #__sportsmanagement_project_team AS tt2 ON m.projectteam2_id=tt2.id
//			LEFT JOIN #__sportsmanagement_team AS t1 ON t1.id=tt1.team_id
//			LEFT JOIN #__sportsmanagement_team AS t2 ON t2.id=tt2.team_id
//            where prepro.prediction_id = " . $prediction_id;
					
		//$query = 'SELECT t.id, t.name FROM #__joomleague_team t ORDER BY name';
		$db->setQuery( $query );
        
        $mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
		$teams = $db->loadObjectList();
        
        if ( !$teams )
        {
        $mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
        }

//$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' teams<br><pre>'.print_r($teams,true).'</pre>'),'');

//$mainframe->enqueueMessage(JText::_('teams -> <pre> '.print_r($teams,true).'</pre><br>' ),'Notice');
		
    //$mitems = array(JHTML::_('select.option', '-1', '- '.JText::_('Do not use').' -'));
    $mitems = array();
    
		foreach ( $teams as $team ) {
			$mitems[] = JHTML::_('select.option',  $team->id, '&nbsp;'.$team->match_date. ' ( '.$team->roundname.' ) ' . ' -> [ ' .$team->home .' - '.  $team->away . ' ] ' );
		}
		
		//$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', 'class="inputbox" size="50" multiple="multiple" ', 'value', 'text', $value );
        $output= JHtml::_('select.genericlist',  $mitems, $this->name.'[]', 'class="inputbox" multiple="multiple" size="10"', 'value', 'text', $this->value, $this->id );
		return $output;
	}
}
 