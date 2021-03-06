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

jimport( 'joomla.application.component.view' );

/**
 * sportsmanagementViewTeams
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementViewTeams extends JViewLegacy
{
	/**
	 * sportsmanagementViewTeams::display()
	 * 
	 * @param mixed $tpl
	 * @return void
	 */
	function display( $tpl = null )
	{
		// Get a reference of the page instance in joomla
		$document= JFactory::getDocument();
        // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;

		$model = $this->getModel();
		$config = sportsmanagementModelProject::getTemplateConfig($this->getName(),$jinput->getInt('cfg_which_database',0));

		$this->assign( 'project', sportsmanagementModelProject::getProject($jinput->getInt('cfg_which_database',0)) );
		$this->assign( 'division', sportsmanagementModelProject::getDivision($jinput->getInt( "division", 0 ),$jinput->getInt('cfg_which_database',0)) );
		$this->assign( 'overallconfig', sportsmanagementModelProject::getOverallConfig($jinput->getInt('cfg_which_database',0)) );
		$this->assignRef( 'config', $config );

		//$this->assignRef( 'teams', $model->getTeams() );
        $this->assign( 'teams', sportsmanagementModelProject::getTeams($jinput->getInt( "division", 0 ),'name',$jinput->getInt('cfg_which_database',0)) );
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' teams<br><pre>'.print_r($this->teams,true).'</pre>'),'');

		// Set page title
		$pageTitle = JText::_( 'COM_SPORTSMANAGEMENT_TEAMS_TITLE' );
		if ( isset( $this->project ) )
		{
			$pageTitle .= " " . $this->project->name;
			if ( isset( $this->division ) )
			{
				$pageTitle .= " : ". $this->division->name;
			}
		}
		$document->setTitle( $pageTitle );
        
        $this->headertitle = JText::_( 'COM_SPORTSMANAGEMENT_TEAMS_TITLE' );

		parent::display( $tpl );
	}
}
?>