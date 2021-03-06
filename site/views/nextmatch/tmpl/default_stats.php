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

?>

<h2><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_H2H'); ?></h2>
<table class="table">
	<thead>
        <tr class="sectiontableheader" align="center">
        <th class="h2h" width="33%">
            <?php
            if ( !is_null ( $this->teams ) )
            {
                echo $this->teams[0]->name;
            }
            else
            {
                echo JText::_( "COM_SPORTSMANAGEMENT_NEXTMATCH_UNKNOWNTEAM" );
            }
            ?>
        </th>
			
			<th  class="h2h" width="33%">
                <?php
                echo JText::_( 'COM_SPORTSMANAGEMENT_NEXTMATCH_STATS' );
                ?>
            </th>
        <th class="h2h">
            <?php
            if ( !is_null ( $this->teams ) )
            {
                echo $this->teams[1]->name;
            }
            else
            {
                echo JText::_( "COM_SPORTSMANAGEMENT_NEXTMATCH_UNKNOWNTEAM" );
            }
            ?>
        </th>
		
        </tr>
	</thead>
    <?php
    if ($this->config['show_chances'] == 1) 
	{
	?>
        <tr class="sectiontableentry1">
            <td class="valueleft">
                <?php
                echo $this->chances[0]."%";
                ?>
            </td>
            <td class="statlabel">
                <?php
                echo JText::_( 'COM_SPORTSMANAGEMENT_NEXTMATCH_CHANCES' );
                ?>
            </td>
            <td class="valueright">
                <?php
                echo $this->chances[1]."%";
                ?>
            </td>
        </tr>
    <?php
	}
	
    if ($this->config['show_current_rank'] == 1) 
	{
    ?>		
        <tr class="sectiontableentry2">
            <td class="valueleft">
                <?php
                echo $this->homeranked->rank;
                ?>
            </td>
            <td class="statlabel">
                <?php
                echo JText::_( 'COM_SPORTSMANAGEMENT_NEXTMATCH_CURRENT_RANK' );
                ?>
            </td>
            <td class="valueright">
                <?php
                echo $this->awayranked->rank;
                ?>
            </td>
        </tr>
    <?php
    }

    if ( $this->config['show_match_count'] == 1 )
    {
    ?>
        <tr class="sectiontableentry1">
            <td class="valueleft">
                <?php
                echo $this->homeranked->cnt_matches;
                ?>
            </td>
            <td class="statlabel">
                <?php
                echo JText::_( 'COM_SPORTSMANAGEMENT_NEXTMATCH_COUNT_MATCHES' );
                ?>
            </td>
            <td class="valueright">
                <?php
                echo $this->awayranked->cnt_matches;
                ?>
            </td>
        </tr>
    <?php
    }

    if ( $this->config['show_match_total'] == 1 )
    {
        ?>
        <tr class="sectiontableentry2">
            <td class="valueleft">
                <?php
                printf( "%s/%s/%s", $this->homeranked->cnt_won, $this->homeranked->cnt_draw, $this->homeranked->cnt_lost);
                ?>
            </td>
            <td class="statlabel">
                   <?php
                echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_TOTAL');
                ?>
            </td>
            <td class="valueright">
                <?php
                printf( "%s/%s/%s", $this->awayranked->cnt_won, $this->awayranked->cnt_draw, $this->awayranked->cnt_lost);
                ?>
            </td>
        </tr>
    <?php
    }

if ( $this->config['show_match_total_home'] == 1 )
{
?>
    <tr class="sectiontableentry1">
        <td class="valueleft">
        <?php printf(
                "%s/%s/%s",
                $this->homeranked->cnt_won_home,
                $this->homeranked->cnt_draw_home,
                $this->homeranked->cnt_lost_home);?>
        </td>
        <td class="statlabel">
			<?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_HOME');?>
		</td>
        <td class="valueright">
        <?php printf(
                "%s/%s/%s",
                $this->awayranked->cnt_won_home,
                $this->awayranked->cnt_draw_home,
                $this->awayranked->cnt_lost_home);?>
        </td>
    </tr>
<?php
}
?>
<?php
if ( $this->config['show_match_total_away'] == 1 )
{
?>
    <tr class="sectiontableentry2">
        <td class="valueleft">
        <?php printf(
                "%s/%s/%s",
                $this->homeranked->cnt_won-$this->homeranked->cnt_won_home,
                $this->homeranked->cnt_draw-$this->homeranked->cnt_draw_home,
                $this->homeranked->cnt_lost-$this->homeranked->cnt_lost_home);?>
        </td>
        <td class="statlabel">
			<?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_AWAY');?>
		</td>
        <td class="valueright">
        <?php printf(
                "%s/%s/%s",
                $this->awayranked->cnt_won-$this->awayranked->cnt_won_home,
                $this->awayranked->cnt_draw-$this->awayranked->cnt_draw_home,
                $this->awayranked->cnt_lost-$this->awayranked->cnt_lost_home);?>
        </td>
    </tr>
<?php
}
?>
<?php
if ( $this->config['show_match_points'] == 1 )
{
?>
    <tr class="sectiontableentry1">
        <td class="valueleft"><?php echo JSMRankingTeam::getPoints();?></td>
        <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_POINTS');?></td>
        <td class="valueright"><?php echo JSMRankingTeam::getPoints();?></td>
    </tr>
<?php
}
?>
<?php
if ( $this->config['show_match_goals'] == 1 )
{
?>
    <tr class="sectiontableentry2">
        <td class="valueleft">
        <?php printf(
                "%s : %s",
                $this->homeranked->sum_team1_result,
                $this->homeranked->sum_team2_result);?>
        </td>
        <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_GOALS');?></td>
        <td class="valueright">
        <?php printf(
                "%s : %s",
                $this->awayranked->sum_team1_result,
                $this->awayranked->sum_team2_result);?>
        </td>
    </tr>
<?php
}
?>
<?php
if ( $this->config['show_match_diff'] == 1 )
{
?>
    <tr class="sectiontableentry1">
        <td class="valueleft">
        <?php echo $this->homeranked->diff_team_results;?>
        </td>
        <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_DIFFERENCE');?></td>
        <td class="valueright">
        <?php echo $this->awayranked->diff_team_results;?>
        </td>
    </tr>
<?php
}
?>

<?php if ( $this->config['show_match_highest_stats'] == 1 ): ?>
	
	<?php if ($this->config['show_match_highest_won'] == 1): ?>
		<tr class="sectiontableentry2">
      <td class="valueleft">
	    	<?php if ($stat = $this->home_highest_home_win): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->home_highest_home_win->pid,$this->home_highest_home_win->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
      <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_HIGHEST_WON_HOME');?></td>
      <td class="valueright">
	    	<?php if ($stat = $this->away_highest_home_win): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->away_highest_home_win->pid,$this->away_highest_home_win->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
    </tr>
	<?php endif; ?>

	<?php if ( $this->config['show_match_highest_loss'] == 1 ): ?>
    <tr class="sectiontableentry1">
      <td class="valueleft">
	    	<?php if ($stat = $this->home_highest_home_def): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->home_highest_home_def->pid,$this->home_highest_home_def->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
      <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_HIGHEST_LOSS_HOME');?></td>
      <td class="valueright">
	    	<?php if ($stat = $this->away_highest_home_def): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->away_highest_home_def->pid,$this->away_highest_home_def->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
    </tr>
	<?php endif; ?>

	<?php if ( $this->config['show_match_highest_won_away'] == 1 ): ?>
    <tr class="sectiontableentry2">
      <td class="valueleft">
	    	<?php if ($stat = $this->home_highest_away_win): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->home_highest_away_win->pid,$this->home_highest_away_win->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
      <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_HIGHEST_WON_AWAY');?></td>
      <td class="valueright">
	    	<?php if ($stat = $this->away_highest_away_win): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->away_highest_away_win->pid,$this->away_highest_away_win->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
    </tr>
	<?php endif; ?>

	<?php if ($this->config['show_match_highest_loss_away'] == 1 ): ?>
    <tr class="sectiontableentry1">
      <td class="valueleft">
	    	<?php if ($stat = $this->home_highest_away_def): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->home_highest_away_def->pid,$this->home_highest_away_def->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
      <td class="statlabel"><?php echo JText::_('COM_SPORTSMANAGEMENT_NEXTMATCH_HIGHEST_LOSS_AWAY');?></td>
      <td class="valueright">
	    	<?php if ($stat = $this->away_highest_away_def): ?>
	        	<?php echo JHtml::link( sportsmanagementHelperRoute::getMatchReportRoute( $this->away_highest_away_def->pid,$this->away_highest_away_def->mid,JRequest::getInt('cfg_which_database',0) ), 
	        													sprintf("%s - %s %s:%s", $stat->hometeam, $stat->awayteam, $stat->homegoals, $stat->awaygoals) ); ?>
	      <?php else: ?>
	      	----
	      <?php endif; ?>
      </td>
    </tr>
	<?php endif; ?>
<?php endif; ?>

</table>

<!-- Main END -->
<br>
<br>

