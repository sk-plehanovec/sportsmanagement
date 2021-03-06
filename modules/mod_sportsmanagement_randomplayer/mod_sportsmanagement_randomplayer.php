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

// no direct access
defined('_JEXEC') or die('Restricted access');

if (! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

if ( !defined('JSM_PATH') )
{
DEFINE( 'JSM_PATH','components/com_sportsmanagement' );
}

require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'helpers'.DS.'sportsmanagement.php');
require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'models'.DS.'databasetool.php');
require_once(JPATH_SITE.DS.JSM_PATH.DS.'helpers'.DS.'route.php');
require_once(JPATH_SITE.DS.JSM_PATH.DS.'helpers'.DS.'countries.php');
require_once(JPATH_SITE.DS.JSM_PATH.DS.'models'.DS.'project.php');

$paramscomponent = JComponentHelper::getParams( 'com_sportsmanagement' );
//$database_table	= $paramscomponent->get( 'cfg_which_database_table' );
//$show_debug_info = $paramscomponent->get( 'show_debug_info' );  
//$show_query_debug_info = $paramscomponent->get( 'show_query_debug_info' ); 
if ( !defined('COM_SPORTSMANAGEMENT_TABLE') )
{
DEFINE( 'COM_SPORTSMANAGEMENT_TABLE',$paramscomponent->get( 'cfg_which_database_table' ) );
}
if ( !defined('COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO') )
{
DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO',$paramscomponent->get( 'show_debug_info' ) );
}
if ( !defined('COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO') )
{
DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO',$paramscomponent->get( 'show_query_debug_info' ) );
}

if (! defined('COM_SPORTSMANAGEMENT_CFG_WHICH_DATABASE'))
{
DEFINE( 'COM_SPORTSMANAGEMENT_CFG_WHICH_DATABASE',$paramscomponent->get( 'cfg_which_database' ) );
}
if ( COM_SPORTSMANAGEMENT_CFG_WHICH_DATABASE || JRequest::getInt( 'cfg_which_database', 0 ) )
{
if (! defined('COM_SPORTSMANAGEMENT_PICTURE_SERVER'))
{    
DEFINE( 'COM_SPORTSMANAGEMENT_PICTURE_SERVER',$paramscomponent->get( 'cfg_which_database_server' ) );
}    
}
else
{
if (! defined('COM_SPORTSMANAGEMENT_PICTURE_SERVER'))
{        
DEFINE( 'COM_SPORTSMANAGEMENT_PICTURE_SERVER',JURI::root() );
}    
}

// get helper
require_once (dirname(__FILE__).DS.'helper.php');



$list = modJSMRandomplayerHelper::getData($params);

$document = JFactory::getDocument();
//add css file
$document->addStyleSheet(JURI::base().'modules/mod_sportsmanagement_randomplayer/css/mod_sportsmanagement_randomplayer.css');

require(JModuleHelper::getLayoutPath('mod_sportsmanagement_randomplayer'));