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

defined( '_JEXEC' ) or die( 'Restricted access' );

if ( !isset ( $this->project ) )
{
	JError::raiseWarning( 'ERROR_CODE', JText::_( 'Error: ProjectID was not submitted in URL or selected project was not found in database!' ) );
}
else
{
	/*
	// only for testing purposes. Should be marked out in public revision
	$this->config['show_match_number']=1;
	$this->config['show_events']=1;
	$this->config['show_division']=1;
	$this->config['switch_home_guest']=1;
	$this->config['show_time']=1;
	$this->project->fav_team_color='blue';
	$this->project->fav_team_text_color='#FFFFFF';
	$this->config['show_playground']=1;
	$this->config['show_playground_alert']=1;
	$this->config['show_referee']=1;
	$this->config['show_dnp_teams']=1;
	$this->config['show_referee']=1;

	$this->config['result_style'] = 1;
	*/
	?>
	<div id="jlg_ranking_table" align="center">
		<br />
		<?php
		if ( count( $this->matches ) > 0 )
		{
			switch ( $this->config['result_style'] )
			{
				case 4:
							{
								echo $this->loadTemplate('results_style_dfcday');
							}
							break;
        case 3:
							{
								echo $this->loadTemplate('results_style3');
							}
							break;

				case 0:
				case 1:
				case 2:				
				default:
							{
								echo $this->loadTemplate('results_style0');
							}
							break;
			}
		}
		?>
	</div>
	<!-- Main END -->
	<?php
	if ($this->config['show_dnp_teams']) { echo $this->loadTemplate('freeteams'); }
}
?>