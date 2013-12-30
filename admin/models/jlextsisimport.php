<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                jlextsisimport.php
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
$option = JRequest::getCmd('option');

$maxImportTime=JComponentHelper::getParams($option)->get('max_import_time',0);
if (empty($maxImportTime))
{
	$maxImportTime=480;
}
if ((int)ini_get('max_execution_time') < $maxImportTime){@set_time_limit($maxImportTime);}

$maxImportMemory=JComponentHelper::getParams($option)->get('max_import_memory',0);
if (empty($maxImportMemory))
{
	$maxImportMemory='150M';
}
if ((int)ini_get('memory_limit') < (int)$maxImportMemory){@ini_set('memory_limit',$maxImportMemory);}


jimport( 'joomla.application.component.model' );
jimport('joomla.html.pane');

require_once( JPATH_ADMINISTRATOR . DS. 'components'.DS.$option. DS. 'helpers' . DS . 'csvhelper.php' );
require_once( JPATH_ADMINISTRATOR . DS. 'components'.DS.$option. DS. 'helpers' . DS . 'ical.php' );
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS. 'helpers' . DS . 'countries.php');


// import JArrayHelper
jimport( 'joomla.utilities.array' );
jimport( 'joomla.utilities.arrayhelper' ) ;

// import JFile
jimport('joomla.filesystem.file');
jimport( 'joomla.utilities.utility' );



class sportsmanagementModeljlextsisimport extends JModel
{

var $_datas=array();
var $_league_id=0;
var $_season_id=0;
var $_sportstype_id=0;
var $import_version='';
var $debug_info = false;
var $_project_id = 0;
var $_sis_art = 1;
var $_sis_datei = '';

function getData()
	{
  //global $mainframe, $option;
  $option = JRequest::getCmd('option');
  $mainframe = JFactory::getApplication();
  $document	= JFactory::getDocument();
  $post = JRequest::get ( 'post' );

$country = '';
$exportpositioneventtype = array();  
$exportplayer = array();
$exportpersons = array();
$exportpersonstemp = array();
$exportclubs = array();
$exportclubsstandardplayground = array();
$exportplaygroundclubib = array();
$exportteams = array();
$exportteamstemp = array();
$exportteamplayer = array();
$exportprojectteam = array();
$exportprojectteams = array();
$exportreferee = array();
$exportprojectposition = array();
$exportposition = array();
$exportparentposition = array();
$exportplayground = array();
$exportplaygroundtemp = array();

$exportteamplaygroundtemp = array();

$exportround = array();
$exportmatch = array();
$exportmatchplayer = array();
$exportmatchevent = array();
$exportevent = array();  
$exportpositiontemp = array(); 

$exportposition = array();
$exportparentposition = array();
$exportprojectposition = array();

$exportmatchreferee = array();

$exportmatchplan = array();

$params = JComponentHelper::getParams( $option );
        $sis_xmllink	= $params->get( 'sis_xmllink' );
        $sis_nummer	= $params->get( 'sis_meinevereinsnummer' );
        $sis_passwort	= $params->get( 'sis_meinvereinspasswort' );
		//$mainframe->enqueueMessage(JText::_('sis_xmllink<br><pre>'.print_r($sis_xmllink,true).'</pre>'   ),'');
        //$mainframe->enqueueMessage(JText::_('sis_meinevereinsnummer<br><pre>'.print_r($sis_nummer,true).'</pre>'   ),'');
        //$mainframe->enqueueMessage(JText::_('sis_meinvereinspasswort<br><pre>'.print_r($sis_passwort,true).'</pre>'   ),'');
        //$liganummer = '001514505501506501000000000000000003000';
        $liganummer = $post ['liganummer'];
        
        $linkresults = self::getLink($sis_nummer,$sis_passwort,$liganummer,$this->_sis_art,$sis_xmllink);
        //$mainframe->enqueueMessage(JText::_('linkresults<br><pre>'.print_r($linkresults,true).'</pre>'   ),'');
        
        
        $linkspielplan = self::getSpielplan($linkresults,$liganummer,$this->_sis_art);
        //$mainframe->enqueueMessage(JText::_('linkspielplan<br><pre>'.print_r($linkspielplan,true).'</pre>'   ),'');

switch ($sis_xmllink)
{
    case 'http://www.sis-handball.de':
    $country = 'DEU';
    break;
    case 'http://www.sis-handball.at':
    $country = 'AUT';
    break;
    
}
  
  $temp = new stdClass();
  $temp->name = '';
  $this->_datas['season'] = $temp;

  $temp = new stdClass();
  $temp->id = 1;
  $temp->name = 'COM_SPORTSMANAGEMENT_ST_HANDBALL';
  $this->_datas['sportstype'] = $temp;

  
  
//foreach ($linkspielplan->Spielklasse as $tempklasse) 
//        {
//        $projectname = $tempklasse->Name;    
//		}

//$mainframe->enqueueMessage(JText::_('Spielklasse->Name<br><pre>'.print_r($linkspielplan->Spielklasse->Name,true).'</pre>'   ),'');

        $projectname = (string) $linkspielplan->Spielklasse->Name;        
        
$temp = new stdClass();
  $temp->name = $projectname;
  $temp->exportRoutine = '2010-09-19 23:00:00';  
  $this->_datas['exportversion'] = $temp;

$temp = new stdClass();
  $temp->name = $projectname;
  $temp->alias = $projectname;
  $temp->short_name = $projectname;
  $temp->middle_name = $projectname;
  $temp->country = $country;
  $this->_datas['league'] = $temp;
  
  $temp = new stdClass();
  $temp->name = $projectname;
  $temp->serveroffset = 0;
  $temp->sports_type_id = 1;
  $temp->project_type = 'SIMPLE_LEAGUE';
  $temp->current_round_auto = '2';
  $temp->auto_time = '2880';
  $temp->start_date = '';  
  $temp->start_time = '';
  $temp->game_regular_time = '60';
  $temp->game_parts = '2';
  $temp->halftime = '15';
  $temp->points_after_regular_time = '2,1,0';
  $temp->use_legs = '0';
  $temp->allow_add_time = '0';
  $temp->add_time = '30';
  $temp->points_after_add_time = '2,1,0';
  $temp->points_after_penalty = '2,1,0';
  $this->_datas['project'] = $temp;

// spielplan auswerten
foreach ($linkspielplan->Spiel as $tempspiel) 
{
// das spiele
$tempmatch = new stdClass();
$tempmatch->id = (string) $tempspiel->Liga;
$tempmatch->round_id = (string) $tempspiel->Spieltag;
$tempmatch->match_date = (string) $tempspiel->Date." ".(string) $tempspiel->vonUhrzeit;
$tempmatch->match_number = (string) $tempspiel->Nummer;
$tempmatch->published = 1;
$tempmatch->count_result = 1;
$tempmatch->show_report = 1;
$tempmatch->projectteam1_id = (string) $tempspiel->HeimNr;
$tempmatch->projectteam2_id = (string) $tempspiel->GastNr;



if ( (string) $tempspiel->Tore1 && (string) $tempspiel->Tore2 )
{
$tore_team_1 = array();
$tore_team_2 = array();    
$tore_team_1[] = (string) $tempspiel->Tore01; 
$tore_team_1[] = (string) $tempspiel->Tore1; 
$tore_team_2[] = (string) $tempspiel->Tore02;
$tore_team_2[] = (string) $tempspiel->Tore2;

$tempmatch->team1_result = (string) $tempspiel->Tore1;
$tempmatch->team1_result_split = implode(";",$tore_team_1);
$tempmatch->team2_result = (string) $tempspiel->Tore2;
$tempmatch->team2_result_split = implode(";",$tore_team_2);
}
    
$tempmatch->summary = '';
$tempmatch->playground_id = (string) $tempspiel->Halle;

$exportmatch[] = $tempmatch;  
      
// runden
if (array_key_exists((string) $tempspiel->Spieltag, $exportround))
{
}
else
{
$temp = new stdClass();
$temp->id = (string) $tempspiel->Spieltag;
$temp->roundcode = (string) $tempspiel->Spieltag;
$temp->name = (string) $tempspiel->Spieltag.'. Spieltag';
$temp->alias = (string) $tempspiel->Spieltag.'-spieltag';
$temp->round_date_first = '';
$temp->round_date_last = '';
$exportround[(string) $tempspiel->Spieltag] = $temp;
}
    
// personen
if (array_key_exists((string) $tempspiel->Schiri, $exportpersonstemp))
{
}
else
{
$exportpersonstemp[(string) $tempspiel->Schiri] = (string) $tempspiel->GespannName; 
$temp = new stdClass();
$temp->id = (string) $tempspiel->Schiri;
$temp->person_id = (string) $tempspiel->Schiri;
$temp->project_position_id = 1000;
$exportreferee[] = $temp;

$temp = new stdClass();
$temp->id = (string) $tempspiel->Schiri;
$temp->lastname = (string) $tempspiel->GespannName;
$temp->firstname = '';
$temp->nickname = '';
$temp->knvbnr = (string) $tempspiel->Schiri;
$temp->unique_id = (string) $tempspiel->Schiri;
$temp->location = '';
$temp->birthday = '0000-00-00';
$temp->country = $country;
$temp->position_id = 1000;
$temp->info = 'Schiri';
$exportpersons[] = $temp;    
}

//schiedsrichter
$tempmatchreferee = new stdClass();
$tempmatchreferee->id = (string) $tempspiel->Schiri; 
$tempmatchreferee->match_id = (string) $tempspiel->Liga; 
$tempmatchreferee->project_referee_id = (string) $tempspiel->Schiri; 
$tempmatchreferee->project_position_id = 1000; 
$exportmatchreferee[] = $tempmatchreferee;

// sporthallen
if (  array_key_exists((string) $tempspiel->HallenName, $exportplaygroundtemp) ) 
{
}
else
{
$exportplaygroundtemp[(string) $tempspiel->HallenName] = (string) $tempspiel->Halle;

$temp = new stdClass();
$temp->id = $tempspiel->Halle;
$temp->name = (string) $tempspiel->HallenName;
$temp->short_name = (string) $tempspiel->HallenName;
$temp->alias = (string) $tempspiel->HallenName;
$temp->club_id = (string) $tempspiel->HeimNr;
$temp->address = (string) $tempspiel->HallenStrasse;
$teile = explode(" ",(string) $tempspiel->HallenOrt);
$temp->zipcode = $teile[0];
$temp->city = $teile[1];
$temp->country = $country;
$temp->max_visitors = 0;
$exportplayground[] = $temp;

}

// heimmannschaft
if (  array_key_exists((string) $tempspiel->Heim, $exportteamstemp) ) 
{
}
else
{
$exportteamstemp[(string) $tempspiel->Heim] = (string) $tempspiel->HeimNr;
}

// gastmannschaft
if (  array_key_exists((string) $tempspiel->Gast, $exportteamstemp) ) 
{
}
else
{
$exportteamstemp[(string) $tempspiel->Gast] = (string) $tempspiel->GastNr;
}



			
}


// teams verarbeiten
foreach ( $exportteamstemp as $key => $value )
{
// team
$temp = new stdClass();
$temp->id = $value;
$temp->club_id = $value;
$temp->name = $key;
$temp->middle_name = $key;
$temp->short_name = $key;
//$temp->info = '';
$temp->info = 'Herren';
$temp->extended = '';
$exportteams[] = $temp;

//$standard_playground = $exportteamplaygroundtemp[$key];
//$standard_playground_nummer = $exportplaygroundtemp[$standard_playground];

// club
$temp = new stdClass();
$temp->id = $value;
$temp->unique_id = $value;
$temp->name = $key;
$temp->country = $country;
$temp->extended = '';
//$temp->standard_playground = $standard_playground_nummer;
$exportclubs[] = $temp;

// projektteam
$temp = new stdClass();
$temp->id = $value;
$temp->team_id = $value;
$temp->project_team_id = $value;
$temp->is_in_score = 1;
//$temp->standard_playground = $standard_playground_nummer;
$exportprojectteams[] = $temp;

}

// schiedsrichterpositionen
$temp = new stdClass();
$temp->id = 1;
$temp->name = 'Schiedsrichter';
$temp->alias = 'Schiedsrichter';
$temp->published = 1;
$exportparentposition[] = $temp;

$temp = new stdClass();
$temp->id = 1000;
$temp->name = 'Spielleitung';
$temp->alias = 'Spielleitung';
$temp->parent_id = 1;
$temp->published = 1;
$temp->persontype = 3;
$exportposition[] = $temp;

$temp = new stdClass();
$temp->id = 1001;
$temp->name = 'Assistent 1';
$temp->alias = 'Assistent 1';
$temp->parent_id = 1;
$temp->published = 1;
$temp->persontype = 3;
$exportposition[] = $temp;

$temp = new stdClass();
$temp->id = 1002;
$temp->name = 'Assistent 2';
$temp->alias = 'Assistent 2';
$temp->parent_id = 1;
$temp->published = 1;
$temp->persontype = 3;
$exportposition[] = $temp;

$temp = new stdClass();
$temp->id = 1000;
$temp->position_id = 1000;
$exportprojectposition[] = $temp;
$temp = new stdClass();
$temp->id = 1001;
$temp->position_id = 1001;
$exportprojectposition[] = $temp;
$temp = new stdClass();
$temp->id = 1002;
$temp->position_id = 1002;
$exportprojectposition[] = $temp;


//$mainframe->enqueueMessage(JText::_('exportclubs<br><pre>'.print_r($exportclubs,true).'</pre>'   ),'');
$this->_datas['matchreferee'] = array_merge($exportmatchreferee);
$this->_datas['position'] = array_merge($exportposition);
$this->_datas['projectposition'] = array_merge($exportprojectposition);
$this->_datas['parentposition'] = array_merge($exportparentposition);
$this->_datas['playground'] = array_merge($exportplayground);
$this->_datas['team'] = array_merge($exportteams);
$this->_datas['projectteam'] = array_merge($exportprojectteams);
$this->_datas['club'] = array_merge($exportclubs);
$this->_datas['person'] = array_merge($exportpersons);
$this->_datas['projectreferee'] = array_merge($exportreferee);
$this->_datas['round'] = array_merge($exportround);
$this->_datas['match'] = array_merge($exportmatch);

/**
 * das ganze f�r den standardimport aufbereiten
 */
$output = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
// open the project
$output .= "<project>\n";
// set the version of JoomLeague
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setJoomLeagueVersion());
// set the project datas
if ( isset($this->_datas['project']) )
{
$mainframe->enqueueMessage(JText::_('project Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setProjectData($this->_datas['project']));
}
// set league data of project
if ( isset($this->_datas['league']) )
{
$mainframe->enqueueMessage(JText::_('league Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setLeagueData($this->_datas['league']));
}
// set season data of project
if ( isset($this->_datas['season']) )
{
$mainframe->enqueueMessage(JText::_('season Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setSeasonData($this->_datas['season']));
}
// set sportstype data of project
if ( isset($this->_datas['sportstype']) )
{
$mainframe->enqueueMessage(JText::_('sportstype Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setSportsType($this->_datas['sportstype']));
}
// set the rounds data
if ( isset($this->_datas['round']) )
{
$mainframe->enqueueMessage(JText::_('round Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['round'], 'Round') );
}
// set the teams data
if ( isset($this->_datas['team']) )
{
$mainframe->enqueueMessage(JText::_('team Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['team'], 'JL_Team'));
}
// set the clubs data
if ( isset($this->_datas['club']) )
{
$mainframe->enqueueMessage(JText::_('club Daten '.'generiert'),'');
//$mainframe->enqueueMessage(JText::_('club<br><pre>'.print_r($this->_datas['club'],true).'</pre>'   ),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['club'], 'Club'));
}
// set the matches data
if ( isset($this->_datas['match']) )
{
$mainframe->enqueueMessage(JText::_('match Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['match'], 'Match'));
}
// set the positions data
if ( isset($this->_datas['position']) )
{
$mainframe->enqueueMessage(JText::_('position Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['position'], 'Position'));
}
// set the positions parent data
if ( isset($this->_datas['parentposition']) )
{
$mainframe->enqueueMessage(JText::_('parentposition Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['parentposition'], 'ParentPosition'));
}
// set position data of project
if ( isset($this->_datas['projectposition']) )
{
$mainframe->enqueueMessage(JText::_('projectposition Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['projectposition'], 'ProjectPosition'));
}
// set the matchreferee data
if ( isset($this->_datas['matchreferee']) )
{
$mainframe->enqueueMessage(JText::_('matchreferee Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['matchreferee'], 'MatchReferee'));
}
// set the person data
if ( isset($this->_datas['person']) )
{
$mainframe->enqueueMessage(JText::_('person Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['person'], 'Person'));
}
// set the projectreferee data
if ( isset($this->_datas['projectreferee']) )
{
$mainframe->enqueueMessage(JText::_('projectreferee Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['projectreferee'], 'ProjectReferee'));
}
// set the projectteam data
if ( isset($this->_datas['projectteam']) )
{
$mainframe->enqueueMessage(JText::_('projectteam Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['projectteam'], 'ProjectTeam'));
}
// set playground data of project
if ( isset($this->_datas['playground']) )
{
$mainframe->enqueueMessage(JText::_('playground Daten '.'generiert'),'');
$output .= sportsmanagementHelper::_addToXml(sportsmanagementHelper::_setXMLData($this->_datas['playground'], 'Playground'));
}            


            
// close the project
$output .= '</project>';

$xmlfile = $output;
$file = JPATH_SITE.DS.'tmp'.DS.'joomleague_import.jlg';
JFile::write($file, $xmlfile);



}  

	//get sis link
	function getLink($vereinsnummer,$vereinspasswort,$liganummer,$sis_art,$sis_xmllink) 
    {
		$sislink = $sis_xmllink.'/xmlexport/xml_dyn.aspx?user=%s&pass=%s&art=%s&auf=%s';
		$link = sprintf($sislink, $vereinsnummer, $vereinspasswort, $sis_art, $liganummer );	
		return $link;
	}
    
    
    //get sis spielplan
	function getSpielplan($linkresults,$liganummer,$sis_art) 
    {
        $option = JRequest::getCmd('option');
  $mainframe = JFactory::getApplication();
		// XML File
		$filepath='components/'.$option.'/sisdata/';
        
        //$mainframe->enqueueMessage(JText::_('filepath<br><pre>'.print_r($filepath,true).'</pre>'   ),'');
        
		//File laden
		$datei = ($filepath.'sp_sis_art_'.$sis_art.'_ln_'.$liganummer.'.xml');
		if (file_exists($datei)) 
        {
			$LetzteAenderung = filemtime($datei);
			if ( (time() - $LetzteAenderung) > 1800) {
				if(file_get_contents($linkresults)) {
			 		//Laden
					$content = file_get_contents($linkresults);
					//Parsen
					$doc = DOMDocument::loadXML($content);
					//Altes File l�schen
					unlink($datei);
					//Speichern
					$doc->save($filepath.'sp_sis_art_'.$sis_art.'_ln_'.$liganummer.'.xml');
				}
			}
		} 
        else 
        {
			//Laden
			$content = file_get_contents($linkresults);
            //$mainframe->enqueueMessage(JText::_('content<br><pre>'.print_r($content,true).'</pre>'   ),'');
            
			//Parsen
			$doc = DOMDocument::loadXML($content);
            //$mainframe->enqueueMessage(JText::_('doc<br><pre>'.print_r($doc,true).'</pre>'   ),'');
			//Speichern
			$doc->save($filepath.'sp_sis_art_'.$sis_art.'_ln_'.$liganummer.'.xml');
		}
		$result = simplexml_load_file($datei);
		// XML File end
		foreach ($result->Spiel as $temp) 
        {
			$nummer = substr( $temp->Liga , -3);
			$datum = substr( $temp->SpielVon , 0, 10);
			$datum_en = date("d.m.Y", strToTime($datum));
			$temp->Date = $datum;
            $temp->Nummer = $nummer;
            $temp->Datum = $datum_en;
			$temp->vonUhrzeit = substr( $temp->SpielVon , 11, 8);
			$temp->bisUhrzeit = substr( $temp->SpielBis , 11, 8);
		}
		return $result;
	}
    



}

?>