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

// check if any player returned
$items = count($list['player']);
if (!$items) {
	echo '<p class="modjlgrandomplayer">' . JText::_('NO ITEMS') . '</p>';
	return;
}?>

<div class="modjlgrandomplayer">
<ul>
<?php if ($params->get('show_project_name')):?>
<li class="projectname"><?php echo $list['project']->name; ?></li>
<?php endif; ?> <?php
$person=$list['player'];
$link = sportsmanagementHelperRoute::getPlayerRoute( $list['project']->slug, 
												$list['infoteam']->team_id, 
												$person->slug );
?>

<li class="modjlgrandomplayer"><?php
$picturetext=JText::_( 'JL_PERSON_PICTURE' );
$text = sportsmanagementHelper::formatName(null, $person->firstname, 
												$person->nickname, 
												$person->lastname, 
												$params->get("name_format"));
	
$imgTitle = JText::sprintf( $picturetext .' %1$s', $text);
if ( isset($list['inprojectinfo']->picture) )
{
    $picture = $list['inprojectinfo']->picture;
}
else
{
    $picture = '';
}
$pic = sportsmanagementHelper::getPictureThumb($picture, $imgTitle, $params->get('picture_width'), $params->get('picture_heigth'));
echo '<a href="'.$link.'">'.$pic.'</a>' ;
?></li>
<li class="playerlink">
<?php 
	if($params->get('show_player_flag')) {
		echo JSMCountries::getCountryFlag($person->country)." ";
	}
	if ($params->get('show_player_link'))
	{
		$link = sportsmanagementHelperRoute::getPlayerRoute($list['project']->slug, 
														$list['infoteam']->team_id, 
														$person->slug );
		echo JHTML::link($link, $text);
	}
	else
	{
		echo JText::sprintf( '%1$s', $text);
	}
?>
</li>
<?php if ($params->get('show_team_name')):?>
<li class="teamname">
<?php 
	echo sportsmanagementHelper::getPictureThumb($list['infoteam']->team_picture,
											$list['infoteam']->name,
											$params->get('team_picture_width',21),
											$params->get('team_picture_height',0),
											1)." ";
	$text = $list['infoteam']->name;
	if ($params->get('show_team_link'))
	{
		$link = sportsmanagementHelperRoute::getTeamInfoRoute($list['project']->slug, 
														$list['infoteam']->team_id);
		echo JHTML::link($link, $text);
	}
	else
	{
		echo JText::sprintf( '%1$s', $text);
	}
?>
</li>
<?php endif; ?>
<?php if ( $params->get('show_position_name') && isset($list['inprojectinfo']->position_name) ):?>
<li class="positionname"><?php 
	$positionName = $list['inprojectinfo']->position_name;
	echo JText::_($positionName);?>
</li>
<?php endif; ?>
</ul>
</div>
