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

//echo '<pre>',print_r($this->teams,true),'</pre><br>';

?>

<!-- Main START -->
<table class="table">
	<?php
	if( $this->config['show_logo'] == 1 )
	{
		?>
	<tr class="nextmatch">
		<td class="teamlogo"><?php
			$pic = $this->config['show_picture'];
        
        $picture = $this->teams[0]->$pic;
        if ( !JFile::exists(JPATH_SITE.DS.$picture) )
        {
        $picture = sportsmanagementHelper::getDefaultPlaceholder("team");
        }                        
		?>

<a href="<?php echo COM_SPORTSMANAGEMENT_PICTURE_SERVER.$picture;?>" title="<?php echo $this->teams[0]->name;?>" data-toggle="modal" data-target="#t<?php echo $this->teams[0]->id;?>">
<?PHP
echo JHtml::image(COM_SPORTSMANAGEMENT_PICTURE_SERVER.$picture, $this->teams[0]->name, array('title' => $this->teams[0]->name,'class' => "img-rounded",'width' => $this->config['team_picture_width'] ));      
?>
</a>                        

<div class="modal fade" id="t<?php echo $this->teams[0]->id;?>" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
</div>
<?PHP
echo JHtml::image(COM_SPORTSMANAGEMENT_PICTURE_SERVER.$picture, $this->teams[0]->name, array('title' => $this->teams[0]->name,'class' => "img-rounded" ));
?>
</div>




        </td>
		<td class="vs">&nbsp;</td>
		<td class="teamlogo"><?php

        $picture = $this->teams[1]->$pic;
        if ( !JFile::exists(JPATH_SITE.DS.$picture) )
        {
        $picture = sportsmanagementHelper::getDefaultPlaceholder("team");
        }                         
		?>
<a href="<?php echo COM_SPORTSMANAGEMENT_PICTURE_SERVER.$picture;?>" title="<?php echo $this->teams[1]->name;?>" data-toggle="modal" data-target="#t<?php echo $this->teams[1]->id;?>">
<?PHP
echo JHtml::image(COM_SPORTSMANAGEMENT_PICTURE_SERVER.$picture, $this->teams[1]->name, array('title' => $this->teams[1]->name,'class' => "img-rounded",'width' => $this->config['team_picture_width'] ));      
?>
</a>                        

<div class="modal fade" id="t<?php echo $this->teams[1]->id;?>" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
</div>
<?PHP
echo JHtml::image(COM_SPORTSMANAGEMENT_PICTURE_SERVER.$picture, $this->teams[1]->name, array('title' => $this->teams[1]->name,'class' => "img-rounded" ));
?>
</div>        
        
        </td>
	</tr>
	<?php
	}
	?>
	<tr class="nextmatch">
		<td class="team"><?php
		if ( !is_null ( $this->teams ) )
		{
			echo $this->teams[0]->name;
		}
		else
		{
			echo JText::_( "COM_SPORTSMANAGEMENT_NEXTMATCH_UNKNOWNTEAM" );
		}
		?></td>
		<td class="vs"><?php
		echo JText::_( "COM_SPORTSMANAGEMENT_NEXTMATCH_VS" );
		?></td>
		<td class="team"><?php
		if ( !is_null ( $this->teams ) )
		{
			echo $this->teams[1]->name;
		}
		else
		{
			echo JText::_( "COM_SPORTSMANAGEMENT_NEXTMATCH_UNKNOWNTEAM" );
		}
		?></td>
	</tr>
</table>

	<?php 
        $report_link = sportsmanagementHelperRoute::getMatchReportRoute($this->project->id,$this->match->id,JRequest::getInt('cfg_which_database',0));
					
        if(isset($this->match->team1_result) && isset($this->match->team2_result))
            { ?>
			<div class="notice">
			<?php 
                $text = JText::_( "COM_SPORTSMANAGEMENT_NEXTMATCH_ALREADYPLAYED" );
                echo JHtml::link( $report_link, $text );
			?>
			</div>
			<?php 
            } ?>
                
<br />