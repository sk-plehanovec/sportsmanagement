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

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * JFormFieldProjects
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class JFormFieldProjects extends JFormFieldList
{

	protected $type = 'projects';

//	/**
//	 * JFormFieldProjects::getInput()
//	 * 
//	 * @return
//	 */
//	protected function getInput()
//    {
//    $options = array();
//    $app = JFactory::getApplication();    
//    $val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
//        $value = $this->form->getValue($val,'request');
//        
//        if ( !$value )
//        {
//        $value = $this->form->getValue($val,'params');
//        $div = 'params';
//        }
//        else
//        {
//        $div = 'request';
//        }
//        
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' cfg_which_database -> <br><pre>'.print_r($this->form->getValue('cfg_which_database',$div),true).'</pre>'),'Notice');
//            
//    }
    
    protected function getOptions() 
    {
        $options = array();
        $app = JFactory::getApplication();
		//$db = JFactory::getDBO();
		$lang = JFactory::getLanguage();
        // welche tabelle soll genutzt werden
        $params = JComponentHelper::getParams( 'com_sportsmanagement' );
        $database_table	= $params->get( 'cfg_which_database_table' );
        
        $val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
        $value = $this->form->getValue($val,'request');
        
        if ( !$value )
        {
        $value = $this->form->getValue($val,'params');
        $div = 'params';
        }
        else
        {
        $div = 'request';
        }
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' cfg_which_database -> <br><pre>'.print_r($this->form->getValue('cfg_which_database',$div),true).'</pre>'),'Notice');
        
        $cfg_which_database = $this->form->getValue('cfg_which_database',$div);
        if ( !$cfg_which_database )
        {
            $db = JFactory::getDBO();
        }
        else
        {
            $db = sportsmanagementHelper::getDBConnection(TRUE,$cfg_which_database);
        }
		$extension = "com_sportsmanagement";
		$source = JPATH_ADMINISTRATOR . '/components/' . $extension;
		$lang->load("$extension", JPATH_ADMINISTRATOR, null, false, false)
		||	$lang->load($extension, $source, null, false, false)
		||	$lang->load($extension, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
		||	$lang->load($extension, $source, $lang->getDefault(), false, false);
		
		$query = 'SELECT p.id, concat(p.name, \' ('.JText::_('COM_SPORTSMANAGEMENT_GLOBAL_LEAGUE').': \', l.name, \')\', \' ('.JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SEASON').': \', s.name, \' )\' ) as name 
					FROM #__'.$database_table.'_project AS p 
					LEFT JOIN #__'.$database_table.'_season AS s ON s.id = p.season_id 
					LEFT JOIN #__'.$database_table.'_league AS l ON l.id = p.league_id 
					WHERE p.published=1 ORDER BY p.id DESC';
		$db->setQuery( $query );
		$projects = $db->loadObjectList();
        
//		if($this->required == false) {
//			$mitems = array(JHtml::_('select.option', '', JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT')));
//		}

		$options[] = JHtml::_('select.option', '0', JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT') );
        
        foreach ( $projects as $project ) {
			$options[] = JHtml::_('select.option',  $project->id, '&nbsp;&nbsp;&nbsp;'.$project->name );
		}
//		
//		$output= JHtml::_('select.genericlist',  $mitems, $this->name.'[]', 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $this->value, $this->id );
//		return $output;
$options = array_merge(parent::getOptions(), $options);

		return $options;
        
	}
}
