<?php
/** SportsManagement ein Programm zur Verwaltung f?r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: ? 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie k?nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp?teren
* ver?ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n?tzlich sein wird, aber
* OHNE JEDE GEW?HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew?hrleistung der MARKTF?HIGKEIT oder EIGNUNG F?R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f?r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * sportsmanagementHelperHtml
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementHelperHtml 
{
    static $roundid = 0;
    static $project = array(); 
    static $teams = array(); 


	/**
	 * Return formated match time
	 *
	 * @param object $game
	 * @param array $config
	 * @param array $overallconfig
	 * @param object $project
	 * @return string html
	 */
	public static function showMatchTime(&$game,&$config,&$overallconfig,&$project)
	// overallconfig could be deleted here and replaced below by config as both array were merged in view.html.php
	{
		$output='';

		if (!isset($overallconfig['time_format'])) {
			$overallconfig['time_format']='H:i';
		}
		$timeSuffix=JText::_('COM_SPORTSMANAGEMENT_GLOBAL_CLOCK');
		if ($timeSuffix=='COM_SPORTSMANAGEMENT_GLOBAL_CLOCK') {
			$timeSuffix='%1$s&nbsp;h';
		}

		if (strtotime($game->match_date))
		{
			$matchTime = JHtml::date($game->match_date,$overallconfig['time_format'],'UTC');

			if ($config['show_time_suffix'] == 1)
			{
				$output .= sprintf($timeSuffix,$matchTime);
			}
			else
			{
				$output .= $matchTime;
			}
				
			$config['mark_now_playing']=(isset($config['mark_now_playing'])) ? $config['mark_now_playing'] : 0;

			if ($config['mark_now_playing'])
			{
				$thistime=mktime();
				$time_to_ellapse=($project->halftime * ($project->game_parts - 1)) + $project->game_regular_time;
				if ($project->allow_add_time == 1 && ($game->team1_result == $game->team2_result))
				{
					$time_to_ellapse += $project->add_time;
				}
				$time_to_ellapse=$time_to_ellapse * 60;
				$mydate=preg_split("/-| |:/",$game->match_date);
				$match_stamp=mktime($mydate[3],$mydate[4],$mydate[5],$mydate[1],$mydate[2],$mydate[0]);
				if ($thistime >= $match_stamp && $match_stamp + $time_to_ellapse >= $thistime)
				{
					$match_begin=$output.' ';
					$title=str_replace('%STARTTIME%',$match_begin,trim(htmlspecialchars($config['mark_now_playing_alt_text'])));
					$title=str_replace('%ACTUALTIME%',self::mark_now_playing($thistime,$match_stamp,$config,$project),$title);
					$styletext='';
					if (isset($config['mark_now_playing_blink']) && $config['mark_now_playing_blink'])
					{
						$styletext=' style="text-decoration:blink"';
					}
					$output='<b><i><acronym title="'.$title.'"'.$styletext.'>';
					$output .= $config['mark_now_playing_text'];
					$output .= '</acronym></i></b>';
				}
			}
		}
		else
		{
			$matchTime='--&nbsp;:&nbsp;--';
			if ($config['show_time_suffix'])
			{
				$output .= sprintf($timeSuffix,$matchTime);
			}
			else
			{
				$output .= $matchTime;
			}
		}

		return $output;
	}


	/**
	 * sportsmanagementHelperHtml::showDivisonRemark()
	 * 
	 * @param mixed $hometeam
	 * @param mixed $guestteam
	 * @param mixed $config
	 * @param integer $division_id
	 * @return
	 */
	public static function showDivisonRemark(&$hometeam,&$guestteam,&$config,$division_id = 0)
	{
	   $app = JFactory::getApplication();
       
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' division_id'.'<pre>'.print_r($division_id,true).'</pre>' ),'');
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' project'.'<pre>'.print_r(self::$project,true).'</pre>' ),'');
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' config'.'<pre>'.print_r($config,true).'</pre>' ),'');
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' hometeam'.'<pre>'.print_r($hometeam,true).'</pre>' ),'');
       
		$output = '';
		if ($config['switch_home_guest']) {
			$tmpteam =& $hometeam; $hometeam =& $guestteam; $guestteam =& $tmpteam;
		}
        
/**
* die gruppen aus der spielpaarung setzen
*/        
        if ( empty($hometeam->division_id) )
        {
            $hometeam->division_id = $division_id;
            $division = JTable::getInstance( 'division', 'sportsmanagementTable' );
		    $division->load( $division_id );
            $hometeam->division_name = $division->name;
            $hometeam->division_shortname = $division->shortname;
        }
        if ( empty($guestteam->division_id) )
        {
            $guestteam->division_id = $division_id;
            $division = JTable::getInstance( 'division', 'sportsmanagementTable' );
		    $division->load( $division_id );
            $guestteam->division_name = $division->name;
            $guestteam->division_shortname = $division->shortname;
        }
        
		if	((isset($hometeam) && $hometeam->division_id > 0) && (isset($guestteam) && $guestteam->division_id > 0))
		{
			//TO BE FIXED: Where is spacer defined???
			if (! isset($config['spacer'])) {
				$config['spacer']='/';
			}

			$nametype = 'division_'.$config['show_division_name'];

			if ($config['show_division_link'])
			{
				$link = sportsmanagementHelperRoute::getRankingRoute(self::$project->id,0,0,0,0,$hometeam->division_id);
				$output .= JHtml::link($link,$hometeam->$nametype);
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' link'.'<pre>'.print_r($link,true).'</pre>' ),'');
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' output'.'<pre>'.print_r($output,true).'</pre>' ),'');
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' hometeam->nametype'.'<pre>'.print_r($hometeam->$nametype,true).'</pre>' ),'');
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' nametype'.'<pre>'.print_r($nametype,true).'</pre>' ),'');
			}
			else
			{
				$output .= $hometeam->$nametype;
			}
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' output'.'<pre>'.print_r($output,true).'</pre>' ),'');

			if ($hometeam->division_id != $guestteam->division_id)
			{
				$output .= $config['spacer'];

				if ($config['show_division_link'] == 1)
				{
					$link = sportsmanagementHelperRoute::getRankingRoute(self::$project->id,null,null,null,0,$guestteam->division_id);
					$output .= JHtml::link($link,$guestteam->$nametype);
                    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' link'.'<pre>'.print_r($link,true).'</pre>' ),'');
				}
				else
				{
					$output .= $guestteam->$nametype;
				}
			}
		}
		else
		{
			$output .= '&nbsp;';
		}
		return $output;
	}

	/**
	 * Shows matchday title
	 *
	 * @param string $title
	 * @param int $current_round
	 * @param array $config
	 * @param int $mode
	 * @return string html
	 */
	public static function showMatchdaysTitle($title,$current_round,&$config,$mode=0)
	{
		$cfg_which_database = JRequest::getInt('cfg_which_database',0);
        // Get a db connection.
        $db = sportsmanagementHelper::getDBConnection(TRUE, $cfg_which_database );
        $query = $db->getQuery(true);
        
        $projectid = JRequest::getInt('p',0);
		//$thisproject = JTable::getInstance('Project','sportsmanagementTable');
		//$thisproject->load($projectid);
        
        $query->clear();
        // select some fields
        $query->select('*');
        $query->select('CONCAT_WS( \':\', id, alias ) AS project_slug');
        // from table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project');
        // where
        $query->where('id = '.$projectid);
		$db->setQuery($query);
		$thisproject = $db->loadObject();

		echo ($title != '') ? $title.' - ' : $title;
		if ($current_round > 0)
		{
			//$thisround = JTable::getInstance('Round','sportsmanagementTable');
			//$thisround->load($current_round);
            
            $query->clear();
            // select some fields
		    $query->select('*');
            $query->select('CONCAT_WS( \':\', id, alias ) AS round_slug');
		    // from table
		    $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_round');
            // where
            $query->where('id = '.$current_round);
		    $db->setQuery($query);
		    $thisround = $db->loadObject();

			if ($config['type_section_heading'] == 1 && $thisround->name != '')
			{
				if ($mode == 1)
				{
					$link=sportsmanagementHelperRoute::getRankingRoute($thisproject->project_slug,$thisround->round_slug,0,0,0,0,$cfg_which_database);
					echo JHtml::link($link,$thisround->name);
				}
				else
				{
					echo $thisround->name;
				}
			}
			elseif ($thisround->id > 0)
			{
				echo ' - '.$thisround->id.'. '.JText::_('COM_SPORTSMANAGEMENT_RESULTS_MATCHDAY').'&nbsp;';
			}

			if ($config['show_rounds_dates'] == 1)
			{
				echo " (";
				if (! strstr($thisround->round_date_first,"0000-00-00"))
				{
					echo JHtml::date($thisround->round_date_first, 'COM_SPORTSMANAGEMENT_GLOBAL_CALENDAR_DATE');
				}
				if (($thisround->round_date_last != $thisround->round_date_first) &&
				(! strstr($thisround->round_date_last,"0000-00-00")))
				{
					echo " - ".JHtml::date($thisround->round_date_last, 'COM_SPORTSMANAGEMENT_GLOBAL_CALENDAR_DATE');
				}
				echo ")";
			}
		}
	}

	/**
	 * sportsmanagementHelperHtml::getRoundSelectNavigation()
	 * 
	 * @param mixed $form
	 * @return
	 */
	public static function getRoundSelectNavigation($form,$cfg_which_database = 0)
	{
		$app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $rounds = sportsmanagementModelProject::getRoundOptions('ASC',$cfg_which_database);
//		$division = JRequest::getInt('division',0);
//        $roundid = JRequest::getInt('r',0);
        $division = $jinput->get->get('division', 0, '');
        $roundid = $jinput->get->get('r', 0, '');

		if($form)
        {
			$currenturl = sportsmanagementHelperRoute::getResultsRoute(sportsmanagementModelProject::$_project->slug, $roundid, $division,0,0,null,$cfg_which_database);
			$options = array();
			foreach ($rounds as $r)
			{
				//$link = sportsmanagementHelperRoute::getResultsRoute(sportsmanagementModelProject::$_project->slug, $r->value, $division,0,0,NULL,$cfg_which_database);
                $link = sportsmanagementHelperRoute::getResultsRoute(sportsmanagementModelProject::$_project->slug, $r->slug, $division,0,0,NULL,$cfg_which_database);
				$options[] = JHtml::_('select.option', $link, $r->text);
			}
		} 
        else 
        {
			$currenturl = sportsmanagementHelperRoute::getResultsRoute(sportsmanagementModelProject::$_project->slug, $roundid, $division,0,0,NULL,$cfg_which_database);
			$options = array();
			foreach ($rounds as $r)
			{
				//$link = sportsmanagementHelperRoute::getResultsRoute(sportsmanagementModelProject::$_project->slug, $r->value, $division,0,0,NULL,$cfg_which_database);
                $link = sportsmanagementHelperRoute::getResultsRoute(sportsmanagementModelProject::$_project->slug, $r->slug, $division,0,0,NULL,$cfg_which_database);
				$options[] = JHtml::_('select.option', $link, $r->text);
			}
		}
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $my_text = 'rounds <pre>'.print_r($rounds,true).'</pre>';    
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text); 
        }

        
		return JHtml::_('select.genericlist',$options,'select-round','onchange="top.location.href=this.options[this.selectedIndex].value;"','value','text',$currenturl);
	}

	
	/**
	 * sportsmanagementHelperHtml::showMatchPlayground()
	 * 
	 * @param mixed $game
	 * @param mixed $config
	 * @return
	 */
	public static function showMatchPlayground(&$game,$config = array())
	{
		
        $cfg_which_database = JRequest::getInt('cfg_which_database',0);
        // Get a db connection.
        $db = sportsmanagementHelper::getDBConnection(TRUE, $cfg_which_database );
        $query = $db->getQuery(true);
        
        if (($config['show_playground'] || $config['show_playground_alert']) && isset($game->playground_id))
		{
			if (empty($game->playground_id))
            {
				$game->playground_id = self::$teams[$game->projectteam1_id]->standard_playground;
			}
			if (empty($game->playground_id))
			{
				//$cinfo =& JTable::getInstance('Club','Table');
				//$cinfo->load($this->teams[$game->projectteam1_id]->club_id);
                
                $query->clear();
                // select some fields
		        $query->select('*');
		        // from table
		        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_club');
                // where
                $query->where('id = '.self::$teams[$game->projectteam1_id]->club_id);
		        $db->setQuery($query);
		        $cinfo = $db->loadObject();
                
				$game->playground_id = $cinfo->standard_playground;
				self::$teams[$game->projectteam1_id]->standard_playground = $cinfo->standard_playground;
			}

			if (!$config['show_playground'] && $config['show_playground_alert'])
			{
				if ($this->teams[$game->projectteam1_id]->standard_playground==$game->playground_id)
				{
					echo '-';
					return '';
				}
			}

			$boldStart	= '';
			$boldEnd	= '';
			$toolTipTitle	= JText::_('COM_SPORTSMANAGEMENT_PLAYGROUND_MATCH');
			$toolTipText	= '';
			$playgroundID	= self::$teams[$game->projectteam1_id]->standard_playground;

			if (($config['show_playground_alert']) && (self::$teams[$game->projectteam1_id]->standard_playground!=$game->playground_id))
			{
				$boldStart		= '<b style="color:red; ">';
				$boldEnd		= '</b>';
				$toolTipTitle	= JText::_('COM_SPORTSMANAGEMENT_PLAYGROUND_NEW');
				$playgroundID	= self::$teams[$game->projectteam1_id]->standard_playground;
			}

			//$pginfo =& JTable::getInstance('Playground','sportsmanagementTable');
			//$pginfo->load($game->playground_id);
            
            $query->clear();
            // select some fields
		    $query->select('*');
		    // from table
		    $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_playground');
            // where
            $query->where('id = '.$game->playground_id);
		    $db->setQuery($query);
		    $pginfo = $db->loadObject();
            
            if ( $pginfo )
            {
			$toolTipText	.= $pginfo->name.'&lt;br /&gt;';
			$toolTipText	.= $pginfo->address.'&lt;br /&gt;';
			$toolTipText	.= $pginfo->zipcode.' '.$pginfo->city. '&lt;br /&gt;';
            }
            else
            {
            // Create an object for the record we are going to update.
            $pginfo = new stdClass();
            // Must be a valid primary key value.
            $pginfo->name = '';    
            $pginfo->short_name = '';
            }

			$link = sportsmanagementHelperRoute::getPlaygroundRoute(sportsmanagementModelProject::$_project->id,$game->playground_id,$cfg_which_database);
			$playgroundName = ($config['show_playground_name'] == 'name') ? $pginfo->name : $pginfo->short_name;
			?>
<span class='hasTip'
	title='<?php echo $toolTipTitle; ?> :: <?php echo $toolTipText; ?>'> 
	<?php	echo JHtml::link($link,$boldStart.$playgroundName.$boldEnd); ?> </span>

	<?php
		}
	}

	/**
	 * mark currently playing game
	 *
	 * @param int $thistime
	 * @param int $match_stamp
	 * @param array $config
	 * @param object $project
	 * @return string
	 */
	function mark_now_playing($thistime,$match_stamp,&$config,&$project)
	{
		$whichpart=1;
		$gone_since_begin=intval(($thistime - $match_stamp)/60);
		$parts_time=intval($project->game_regular_time / $project->game_parts);
		if ($project->allow_add_time) {
			$overtime=1;
		}else{$overtime=0;
		}
		$temptext=JText::_('COM_SPORTSMANAGEMENT_RESULTS_LIVE_WRONG');
		for ($temp_count=1; $temp_count <= $project->game_parts+$overtime; $temp_count++)
		{
			$this_part_start=(($temp_count-1) * ($project->halftime + $parts_time));
			$this_part_end=$this_part_start + $parts_time;
			$next_part_start=$this_part_end + $project->halftime;
			if ($gone_since_begin >= $this_part_start && $gone_since_begin <= $this_part_end)
			{
				$temptext=str_replace('%PART%',$temp_count,trim(htmlspecialchars($config['mark_now_playing_alt_actual_time'])));
				$temptext=str_replace('%MINUTE%',($gone_since_begin+1 - ($temp_count-1)*$project->halftime),$temptext);
				break;
			}
			elseif ($gone_since_begin > $this_part_end && $gone_since_begin < $next_part_start)
			{
				$temptext=str_replace('%PART%',$temp_count,trim(htmlspecialchars($config['mark_now_playing_alt_actual_break'])));
				break;
			}
		}
		return $temptext;
	}

	/**
	 * return thumb up/down image url if team won/loss
	 *
	 * @param object $game
	 * @param int $projectteam_id
	 * @param array attributes
	 * @return string image html code
	 */
	public static function getThumbUpDownImg($game, $projectteam_id, $attributes = null)
	{
		$res = sportsmanagementHelper::getTeamMatchResult($game, $projectteam_id);
		if ($res === false) {
			return false;
		}
		
		if ($res == 0) 
		{
			$img = 'media/com_sportsmanagement/jl_images/draw.png';
			$alt = JText::_('COM_SPORTSMANAGEMENT_DRAW');
			$title = $alt;
		}
		else if ($res < 0) 
		{
			$img = 'media/com_sportsmanagement/jl_images/thumbs_down.png';
			$alt = JText::_('COM_SPORTSMANAGEMENT_LOST');
			$title = $alt;
		}
		else 
		{
			$img = 'media/com_sportsmanagement/jl_images/thumbs_up.png';
			$alt = JText::_('COM_SPORTSMANAGEMENT_WON');
			$title = $alt;
		}
		
		// default title attribute, if not specified in passed attributes
		$def_attribs = array('title' => $title);
		if ($attributes) {
			$attributes = array_merge($def_attribs, $attributes);
		}
		else {
			$attributes = $def_attribs;
		}
		
		return JHtml::image($img, $alt, $attributes);	
	}
	/**
	* return thumb up/down image as link with score as title
	*
	* @param object $game
	* @param int $projectteam_id
	* @param array attributes
	* @return string linked image html code
	*/
	public function getThumbScore($game, $projectteam_id, $attributes = null)
	{		
		if (!$img = self::getThumbUpDownImg($game, $projectteam_id, $attributes = null)) {
			return false;
		}
		$txt = $teams[$game->projectteam1_id]->name.' - '.$teams[$game->projectteam2_id]->name.' '.$game->team1_result.' - '. $game->team2_result;
		
		$attribs = array('title' => $txt);
		if (is_array($attributes)) {
			$attribs = array_merge($attributes, $attribs);
		}
		$url = JRoute::_(sportsmanagementHelperRoute::getMatchReportRoute($game->project_slug, $game->slug));
		return JHtml::link($url, $img);
	}
	
	/**
	* return up/down image for ranking
	*
	* @param object $team (rank)
	* @param object $previous (rank)
	* @param int $ptid
	* @return string image html code
	*/	
	public static function getLastRankImg($team,$previous,$ptid,$attributes = null)
	{
		if ( isset( $previous[$ptid]->rank ) )
		{
			$imgsrc = JURI::root() . 'media/com_sportsmanagement/jl_images/';
			if ( ( $team->rank == $previous[$ptid]->rank ) || ( $previous[$ptid]->rank == "" ) )
			{
				$imgsrc .= "same.png";
				$alt	 = JText::_('COM_SPORTSMANAGEMENT_RANKING_SAME');
				$title	 = $alt;
			}
			elseif ( $team->rank < $previous[$ptid]->rank )
			{
				$imgsrc .= "up.png";
				$alt	 = JText::_('COM_SPORTSMANAGEMENT_RANKING_UP');
				$title	 = $alt;
			}
			elseif ( $team->rank > $previous[$ptid]->rank )
			{
				$imgsrc .= "down.png";
				$alt	 = JText::_('COM_SPORTSMANAGEMENT_RANKING_DOWN');
				$title	 = $alt;
			}
			
		$def_attribs = array('title' => $title);
		if ($attributes) {
			$attributes = array_merge($def_attribs, $attributes);
		}
		else {
			$attributes = $def_attribs;
		}
		return JHtml::image($imgsrc,$alt,$attributes);
		}
		
	}

    /**
     * sportsmanagementHelperHtml::printColumnHeadingSortAllTimeRanking()
     * 
     * @param mixed $columnTitle
     * @param mixed $paramName
     * @param mixed $config
     * @param string $default
     * @return void
     */
    public static function printColumnHeadingSortAllTimeRanking( $columnTitle, $paramName, $config = null, $default="DESC" )
	{
		$cfg_which_database = JRequest::getInt('cfg_which_database',0);
        $output = "";
		$img='';
		if ( $config['column_sorting'] || $config == null)
		{
			$params = array(
					"option" => "com_sportsmanagement",
					"view"   => JRequest::getVar("view", "rankingalltime"),
					"p" => JRequest::getInt( "p", 0 ),
                    "l" => JRequest::getInt( "l", 0 ),
					"r" => JRequest::getInt( "r", 0 ),
                    "points" => JRequest::getVar( "points", "" ),
					"type" => JRequest::getVar( "type", "" ) );
	
			if ( JRequest::getVar( 'order', '' ) == $paramName )
			{
				$params["order"] = $paramName;
				$params["dir"] = ( JRequest::getVar( 'dir', '') == 'ASC' ) ? 'DESC' : 'ASC';
				$imgname = 'sort'.(JRequest::getVar( 'dir', '') == 'ASC' ? "02" :"01" ).'.gif';
				$img = JHtml::image(
										'media/com_sportsmanagement/jl_images/' . $imgname,
				$params["dir"] );
			}
			else
			{
				$params["order"] = $paramName;
				$params["dir"] = $default;
			}
            
            $params["cfg_which_database"] = $cfg_which_database;
            
			$query = JURI::buildQuery( $params );
			echo JHtml::link(
			JRoute::_( "index.php?".$query ),
			JText::_($columnTitle),
			array( "class" => "jl_rankingheader" ) ).$img;
		}
		else
		{
			echo JText::_($columnTitle);
		}
	}
    
	/**
	 * sportsmanagementHelperHtml::printColumnHeadingSort()
	 * 
	 * @param mixed $columnTitle
	 * @param mixed $paramName
	 * @param mixed $config
	 * @param string $default
	 * @return void
	 */
	public static function printColumnHeadingSort( $columnTitle, $paramName, $config = null, $default="DESC" )
	{
		$cfg_which_database = JRequest::getInt('cfg_which_database',0);
        $output = "";
		$img='';
		if ( $config['column_sorting'] || $config == null)
		{
			$params = array(
					"option" => "com_sportsmanagement",
					"view"   => JRequest::getVar("view", "ranking"),
					"p" => JRequest::getInt( "p", 0 ),
					"r" => JRequest::getInt( "r", 0 ),
					"type" => JRequest::getVar( "type", "" ) );
	
			if ( JRequest::getVar( 'order', '' ) == $paramName )
			{
				$params["order"] = $paramName;
				$params["dir"] = ( JRequest::getVar( 'dir', '') == 'ASC' ) ? 'DESC' : 'ASC';
				$imgname = 'sort'.(JRequest::getVar( 'dir', '') == 'ASC' ? "02" :"01" ).'.gif';
				$img = JHtml::image(
										'media/com_sportsmanagement/jl_images/' . $imgname,
				$params["dir"] );
			}
			else
			{
				$params["order"] = $paramName;
				$params["dir"] = $default;
			}
            
            $params["cfg_which_database"] = $cfg_which_database;
            
			$query = JURI::buildQuery( $params );
			echo JHtml::link(
			JRoute::_( "index.php?".$query ),
			JText::_($columnTitle),
			array( "class" => "jl_rankingheader" ) ).$img;
		}
		else
		{
			echo JText::_($columnTitle);
		}
	}
	
	/**
	 * sportsmanagementHelperHtml::nextLastPages()
	 * 
	 * @param mixed $url
	 * @param mixed $text
	 * @param mixed $maxentries
	 * @param integer $limitstart
	 * @param integer $limit
	 * @return void
	 */
	public static function nextLastPages( $url, $text, $maxentries, $limitstart = 0, $limit = 10 )
	{
		$latestlimitstart = 0;
		if ( intval( $limitstart - $limit ) > 0 )
		{
			$latestlimitstart = intval( $limitstart - $limit );
		}
		$nextlimitstart = 0;
		if ( ( $limitstart + $limit ) < $maxentries )
		{
			$nextlimitstart = $limitstart + $limit;
		}
		$lastlimitstart = ( $maxentries - ( $maxentries % $limit ) );
		if ( ( $maxentries % $limit ) == 0 )
		{
			$lastlimitstart = ( $maxentries - ( $maxentries % $limit ) - $limit );
		}
	
		echo '<center>';
		echo '<table style="width: 50%; align: center;" cellspacing="0" cellpadding="0" border="0">';
		echo '<tr>';
		echo '<td style="width: 10%; text-align: left;" nowrap="nowrap">';
		if ( $limitstart > 0 )
		{
			$query = JURI::buildQuery(
			array(
					"limit" => $limit,
					"limitstart" => 0 ) );
			echo JHtml::link( $url.$query, '&lt;&lt;&lt;' );
			echo '&nbsp;&nbsp;&nbsp';
			$query = JURI::buildQuery(
			array(
					"limit" => $limit,
					"limitstart" => $latestlimitstart ) );
			echo JHtml::link( $url.$query, '&lt;&lt;' );
			echo '&nbsp;&nbsp;&nbsp;';
		}
		echo '</td>';
		echo '<td style="text-align: center;" nowrap="nowrap">';
		$players_to = $maxentries;
		if ( ( $limitstart + $limit ) < $maxentries )
		{
			$players_to = ( $limitstart + $limit );
		}
		echo sprintf( $text, $maxentries, ($limitstart+1).' - '.$players_to );
		echo '</td>';
		echo '<td style="width: 10%; text-align: right;" nowrap="nowrap">';
		if ( $nextlimitstart > 0 )
		{
			echo '&nbsp;&nbsp;&nbsp;';
			$query = JURI::buildQuery(
			array(
					"limit" => $limit,
					"limitstart" => $nextlimitstart ) );
			echo JHtml::link( $url.$query, '&gt;&gt;' );
			echo '&nbsp;&nbsp;&nbsp';
			$query = JURI::buildQuery(
			array(
					"limit" => $limit,
					"limitstart" => $lastlimitstart ) );
			echo JHtml::link( $url.$query, '&gt;&gt;&gt;' );
		}
		echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo '</center>';
	}
	
}
