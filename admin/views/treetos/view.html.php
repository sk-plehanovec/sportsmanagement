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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');




class sportsmanagementViewTreetos extends sportsmanagementView
{

	public function init ()
	{
		$option		= JRequest::getCmd('option');
		$app	= JFactory::getApplication();
		$this->project_id = $app->getUserState( "$option.pid", '0' );
		$uri 		= JFactory::getURI()->toString();
		$user		= JFactory::getUser();
		
		// Get data from the model
		$items		= $this->get('Data');
		$total		= $this->get('Total');
		$pagination = $this->get('Pagination');
		
		$model = $this->getModel();
		//$projectws = $this->get('Data','project');
        $mdlProject = JModelLegacy::getInstance("Project", "sportsmanagementModel");
	    $projectws = $mdlProject->getProject($this->project_id);
        
		$division = $app->getUserStateFromRequest($option.'tt_division','division','','string');

		//build the html options for divisions
		$divisions[]=JHtmlSelect::option('0',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_DIVISION'));
		$mdlDivisions = JModelLegacy::getInstance("divisions", "sportsmanagementModel");
		if ($res = $mdlDivisions->getDivisions($this->project_id)){
			$divisions=array_merge($divisions,$res);
		}
		$lists['divisions']=$divisions;
		unset($divisions);
	
		$this->assignRef('user', 		$user);
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('projectws',	$projectws);
		$this->assignRef('division',	$division);
		$this->assignRef('total',		$total);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri);
        
        //$this->setLayout('default');

		//$this->addToolbar();
//		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOS_TITLE'),'Tree');

		JToolBarHelper::apply('treeto.saveshort');
		JToolBarHelper::publishList('treeto.publish');
		JToolBarHelper::unpublishList('treeto.unpublish');
		JToolBarHelper::divider();

		JToolBarHelper::addNew('treeto.save');
		JToolBarHelper::deleteList(JText::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOS_WARNING'), 'treeto.remove');
		JToolBarHelper::divider();
        
        parent::addToolbar();

		
	}
}
?>
