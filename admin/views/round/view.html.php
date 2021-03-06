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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * sportsmanagementViewRound
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewRound extends sportsmanagementView
{
	
	/**
	 * sportsmanagementViewRound::init()
	 * 
	 * @return
	 */
	public function init ()
	{
	   $option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
        
        $this->project_id	= $app->getUserState( "$option.pid", '0' );;
        $this->project_art_id	= $app->getUserState( "$option.project_art_id", '0' );;
        
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
        
        //$project_id	= $this->item->project_id;
        $mdlProject = JModelLegacy::getInstance("Project", "sportsmanagementModel");
	    $project = $mdlProject->getProject($this->project_id);
        $this->assignRef('project',$this->project_id);
 
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' info<br><pre>'.print_r($_SERVER['REMOTE_ADDR'],true).'</pre>'   ),'');
        
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;
        
        if ( $this->item->id )
        {
            // alles ok
        }
        else
        {
            $this->form->setValue('round_date_first', null, '0000-00-00');
            $this->form->setValue('round_date_last', null, '0000-00-00');
        }
        
        $this->form->setValue('project_id', null, $this->project_id);
 

	}

	
    
	/**
	* Add the page title and toolbar.
	*
	* @since	1.6
	*/
	protected function addToolbar()
	{
	
		JRequest::setVar('hidemainmenu', true);
        JRequest::setVar('pid', $this->project_id);
        
        $isNew = $this->item->id ? $this->title = JText::_('COM_SPORTSMANAGEMENT_ROUND_EDIT') : $this->title = JText::_('COM_SPORTSMANAGEMENT_ROUND_NEW');
        $this->icon = 'round';
        
	parent::addToolbar();
    

	}
    
    
}
?>
