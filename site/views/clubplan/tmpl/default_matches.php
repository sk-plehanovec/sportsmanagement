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

//echo '<pre>',print_r($this->matches,true),'</pre><br>';

?>
<!-- START: matches -->
<table class="<?php echo $this->config['table_class']; ?>">
<?php
if ($this->config['type_matches'] != 0) {
?>
	<tr class="sectiontableheader">
		<?php if ($this->config['show_matchday']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCHDAY'); ?></th>
		<?php } ;?>
		<?php if ($this->config['show_match_nr']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCH_NR'); ?></th>
		<?php } ;?>		
		<?php if ($this->config['show_match_date']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_DATE');?></th>
		<?php } ;?>
		<?php if ($this->config['show_match_time']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_TIME'); ?></th>
		<?php } ;?>
		<?php if ($this->config['show_time_present']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_TIME_PRESENT'); ?></th>
		<?php } ;?>
		<?php if ($this->config['show_league']==1) { ?>		
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_LEAGUE'); ?></th>
		<?php } ;?>		
		<?php if ($this->config['show_club_logo']==1) { ?>
		<th></th>
		<?php } ?>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<?php if ($this->config['show_club_logo']==1) { ?>
		<th>&nbsp;</th>
		<?php } ?>
		<th>&nbsp;</th>
		<?php if ($this->config['show_referee']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_REFEREE'); ?></th>
		<?php } ;?>
		<?php if ($this->config['show_playground']==1) { ?>
		<th><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_PLAYGROUND'); ?></th>
		<?php } ;?>
		<th colspan=3 align="center"><?php echo JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_RESULT'); ?></th>
		<?php if ($this->config['show_thumbs_picture']==1) { ?>
		<th align="center">&nbsp;</th>
		<?php } ;?>
	</tr>
<?php
}
		$k   = 0;
		$cnt = 0;
		$club_id = JRequest::getInt('cid') != -1 ? JRequest::getInt('cid') : false;
		$prevDate = '';
		foreach ($this->matches as $game)
		{
			if ($this->config['type_matches'] == 0) {
			   $gameDate = strftime("%Y-%m-%d",strtotime($game->match_date));
			   if ($gameDate != $prevDate) {
				?>
					<tr class="sectiontableheader">
						<th colspan="16">
							<?php echo JHtml::date($game->match_date, JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCHDATE'));?>
						</th>
					</tr>
				<?php
					$prevDate = $gameDate;
				}
			}


			//$class = ($k==0)? $this->config['style_class1'] : $this->config['style_class2'];
			$result_link = sportsmanagementHelperRoute::getResultsRoute($game->project_id,$game->roundid);
			$nextmatch_link = sportsmanagementHelperRoute::getNextmatchRoute($game->project_id,$game->match_id);
			$teaminfo1_link = sportsmanagementHelperRoute::getTeamInfoRoute($game->project_id,$game->team1_id);
			$teaminfo2_link = sportsmanagementHelperRoute::getTeamInfoRoute($game->project_id,$game->team2_id);
			$teamstats1_link = sportsmanagementHelperRoute::getTeamStatsRoute($game->project_id,$game->team1_id);
			$teamstats2_link = sportsmanagementHelperRoute::getTeamStatsRoute($game->project_id,$game->team2_id);
			$playground_link = sportsmanagementHelperRoute::getPlaygroundRoute($game->project_id,$game->playground_id);
			$favs = sportsmanagementHelper::getProjectFavTeams($game->project_id);
			$favteams = explode(",",$favs->fav_team);

			if ($this->config['which_link2']==1) {
				$link1 = $teaminfo1_link;
				$link2 = $teaminfo2_link;
			} else if ($this->config['which_link2']==2) {
				$link1 = $teamstats1_link;
				$link2 = $teamstats2_link;
			} else {
				$link1 = null;
				$link2 = null;
			}
			$hometeam               = $game;
			$awayteam               = $game;
			
			$isFavTeam              = false;
			$isFavTeam              = in_array($game->team1_id,$favteams);
			$hometeam->name         = $game->tname1;
			$hometeam->team_id      = $game->team1_id;
			$hometeam->id           = $game->team1_id;
			$hometeam->short_name   = $game->tname1_short;
			$hometeam->middle_name  = $game->tname1_middle;
			$hometeam->project_id   = $game->prid;
			$hometeam->club_id      = $game->t1club_id;
			$hometeam->projectteamid = $game->projectteam1_id;
			$hometeam->club_slug    = $game->club1_slug;
			$hometeam->team_slug    = $game->team1_slug;
			$tname1 = sportsmanagementHelper::formatTeamName($hometeam,'clubplanhome'.$cnt++,$this->config,$isFavTeam, $link1);
			
			$isFavTeam              = false;
			$isFavTeam              = in_array($game->team2_id,$favteams);
			$awayteam->name         = $game->tname2;
			$awayteam->team_id      = $game->team2_id;
			$awayteam->id           = $game->team2_id;
			$awayteam->short_name   = $game->tname2_short;
			$awayteam->middle_name  = $game->tname2_middle;
			$awayteam->project_id   = $game->prid;
			$awayteam->club_id      = $game->t2club_id;
			$awayteam->projectteamid = $game->projectteam2_id;
			$awayteam->club_slug    = $game->club2_slug;
			$awayteam->team_slug    = $game->team2_slug;
			$tname2 = sportsmanagementHelper::formatTeamName($awayteam,'clubplanaway'.$cnt++,$this->config,$isFavTeam, $link2);

			$favStyle = '';
			if ($this->config['highlight_fav'] == 1 && !$club_id) {
				$isFavTeam = in_array($game->team1_id,$favteams) || in_array($game->team2_id, $favteams);
				if ( $isFavTeam && $favs->fav_team_highlight_type == 1 )
				{
					if( trim( $favs->fav_team_color ) != "" )
					{
						$color = trim($favs->fav_team_color);
					}
					$format = "%s";
					$favStyle = ' style="';
					$favStyle .= ($favs->fav_team_text_bold != '') ? 'font-weight:bold;' : '';
					$favStyle .= (trim($favs->fav_team_text_color) != '') ? 'color:'.trim($favs->fav_team_text_color).';' : '';
					$favStyle .= ($color != '') ? 'background-color:' . $color . ';' : '';
					if ($favStyle != ' style="')
					{
					  $favStyle .= '"';
					}
					else {
					  $favStyle = '';
					}
				}
			}

			?>
			<tr class=""<?php echo $favStyle; ?>>
					<?php if ($this->config['show_matchday']==1) { ?>
				<td>
					<?php if ($this->config['which_link']==0) { ?>
					<?php
					echo $game->roundcode ;
					}
					?>
					<?php if ($this->config['which_link']==1) { ?>
					<?php
					echo JHtml::link($result_link,$game->roundcode);
					}
					?>
					<?php if ($this->config['which_link']==2) { ?>
					<?php
					echo JHtml::link($nextmatch_link,$game->roundcode);
					}
					?>
				</td>
					<?php } ;?>
					
					<?php if ($this->config['show_match_nr']==1) { ?>
				<td>
					<?php echo $game->match_number ; ?>
				</td>
					<?php } ;?>
				
				<?php if ($this->config['show_match_date']==1) { ?>
				<td>
					<?php
					echo JHtml::date($game->match_date, JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCHDATE'));
					?>
				</td>
					<?php } ;?>
					
				<?php if ($this->config['show_match_time']==1) { ?>
				<td nowrap="nowrap">
					<?php
					echo sportsmanagementHelperHtml::showMatchTime($game, $this->config, $this->overallconfig, $this->project);
					?>
				</td>
					<?php } ;?>
					
				<?php if ($this->config['show_time_present']==1) { ?>
				<td nowrap="nowrap">
					<?php
					echo $game->time_present;
					?>
				</td>
					<?php } ?>
					
				<?php if ($this->config['show_league']==1) { ?>							
				<td>
					<?php echo $game->l_name; ?>
				</td>
					<?php } ?>				
				<td class="td_r">
					<?php
						echo $tname1;
					?>
				</td>
					<?php if ($this->config['show_club_logo']==1) { 
					   $picture = 'home_'.$this->config['team_picture'];
                       ?>
				<td>
                <a href="<?php echo COM_SPORTSMANAGEMENT_PICTURE_SERVER.$game->$picture;?>" title="<?php echo $game->tname1;?>" data-toggle="modal" data-target="#t<?php echo $game->team1_id;?>">
					<?php echo sportsmanagementModelClubPlan::getClubIconHtmlSimple($game->$picture,$game->club1_country,1); ?>
				</a>

<div class="modal fade" id="t<?php echo $game->team1_id;?>" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
</div>
<?PHP
echo sportsmanagementModelClubPlan::getClubIconHtmlSimple($game->$picture,$game->club1_country,1);
?>
</div>
                  
                </td>
					<?php } ?>				
				<td>
					-
				</td>
					<?php if ($this->config['show_club_logo']==1) { 
					   $picture = 'away_'.$this->config['team_picture'];
                       ?>
				<td>
                <a href="<?php echo COM_SPORTSMANAGEMENT_PICTURE_SERVER.$game->$picture;?>" title="<?php echo $game->tname2;?>" data-toggle="modal" data-target="#t<?php echo $game->team2_id;?>">
					<?php echo sportsmanagementModelClubPlan::getClubIconHtmlSimple($game->$picture,$game->club2_country,1); ?>
				</a>

<div class="modal fade" id="t<?php echo $game->team2_id;?>" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
</div>
<?PHP
echo sportsmanagementModelClubPlan::getClubIconHtmlSimple($game->$picture,$game->club2_country,1);
?>
</div>
                
                </td>
					<?php } ?>
				<td>
					<?php
						echo $tname2;
					?>
				</td>
					<?php if ($this->config['show_referee']==1) { ?>
				<td>
					<?php
					$matchReferees = $this->model->getMatchReferees($game->match_id);
					foreach ($matchReferees AS $matchReferee)
					{
						$referee_link=sportsmanagementHelperRoute::getRefereeRoute($game->project_id,$matchReferee->id);
						echo JHtml::link($referee_link,$matchReferee->firstname." ".$matchReferee->lastname);
						echo '<br />';
					}
					?>
				</td>
					<?php } ;?>
					<?php if ($this->config['show_playground']==1) { ?>
				<td>
					<?php
					echo JHtml::link($playground_link,$game->pl_name);
					?>
				</td>
					<?php } ;?>
					<?php
					$score="";
					if (!$game->alt_decision)
					{
						$e1 =$game->team1_result;
						$e2 =$game->team2_result;
					}
					else
					{
						$e1 =(isset($game->team1_result_decision)) ? $game->team1_result_decision : 'X';
						$e2 =(isset($game->team2_result_decision)) ? $game->team2_result_decision : 'X';
					}
					
					if ($game->cancel==0) {
						$score .= '<td align="center">';
						$score .= $e1;
						$score .= '</td><td align="center">-</td><td align="center">';
						$score .= $e2;
					} else {
						$score .= '<td align="center" valign="top" colspan="3">'.$game->cancel_reason.'</td>';
					}
					echo $score;
					if ($this->config['show_thumbs_picture']==1) {
					   switch ($this->config['type_matches']) {
					   case 1 : // home matches
							$team1=$e1;
							$team2=$e2;
							break;
					   case 2 : // away matches
							$team2=$e1;
							$team1=$e2;
							break;
						default : // home+away matches, but take care of the select club from the menu item to have the icon correct displayed
							if ($game->club1_id == $club_id) {
								$team1=$e1;
								$team2=$e2;
							} else if ($game->club2_id == $club_id) {
								$team1=$e2;
								$team2=$e1;
							} else {
								$team1=$e1;
								$team2=$e2;
							}
					   }
						if(isset($team1) && isset($team2) && ($team1==$team2)) {
							echo '<td align="center" valign="middle">' .
							JHtml::image("media/com_sportsmanagement/jl_images/draw.png",
							"draw.png",
							array("title" => JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCH_DRAW'))
							)."&nbsp;</td>";
						} else {
							if($team1 > $team2) {
								echo '<td align="center" valign="middle">' .
								JHtml::image("media/com_sportsmanagement/jl_images/thumbs_up.png",
								"thumbs_up.png",
								array("title" => JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCH_WON'))
								)."&nbsp;</td>";
							} elseif($team2 > $team1) {
								echo '<td align="center" valign="middle">' .
								JHtml::image("media/com_sportsmanagement/jl_images/thumbs_down.png",
								"thumbs_down.png",
								array("title" => JText::_('COM_SPORTSMANAGEMENT_CLUBPLAN_MATCH_LOST'))
								)."&nbsp;</td>";
							}
							else
							{
								echo "<td>&nbsp;</td>";
							}
						}
					}
					?>
				</tr>
		<?php
		$k=1 - $k;
		} ;
		?>
</table>
<br />
<!-- END: matches -->
