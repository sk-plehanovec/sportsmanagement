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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 

/**
 * sportsmanagementModelstatistic
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelstatistic extends JModelAdmin
{
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_sportsmanagement.message.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
    
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'statistic', $prefix = 'sportsmanagementTable', $config = array()) 
	{
	$config['dbo'] = sportsmanagementHelper::getDBConnection(); 
		return JTable::getInstance($type, $prefix, $config);
	}
    
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		$app = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $db		= $this->getDbo();
        $query = $db->getQuery(true);
        
        $cfg_which_media_tool = JComponentHelper::getParams($option)->get('cfg_which_media_tool',0);
        //$app->enqueueMessage(JText::_('sportsmanagementModelagegroup getForm cfg_which_media_tool<br><pre>'.print_r($cfg_which_media_tool,true).'</pre>'),'Notice');
        // Get the form.
		$form = $this->loadForm('com_sportsmanagement.statistic', 'statistic', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
        
        $form->setFieldAttribute('icon', 'default', JComponentHelper::getParams($option)->get('ph_icon',''));
        $form->setFieldAttribute('icon', 'directory', 'com_'.COM_SPORTSMANAGEMENT_TABLE.'/database/statistics');
        $form->setFieldAttribute('icon', 'type', $cfg_which_media_tool);
        
        $prefix = $app->getCfg('dbprefix');
        
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' prefix<br><pre>'.print_r($prefix,true).'</pre>'),'');
        //$whichtabel = $this->getTable();
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' whichtabel<br><pre>'.print_r($whichtabel,true).'</pre>'),'');
        
        $query->select('*');
			$query->from('information_schema.columns');
            $query->where("TABLE_NAME LIKE '".$prefix."sportsmanagement_statistic' ");
			
			$db->setQuery($query);
            
            //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' dump<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
			$result = $db->loadObjectList();
            //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' result<br><pre>'.print_r($result,true).'</pre>'),'');
            
            foreach($result as $field )
        {
            //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' COLUMN_NAME<br><pre>'.print_r($field->COLUMN_NAME,true).'</pre>'),'');
            //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' DATA_TYPE<br><pre>'.print_r($field->DATA_TYPE,true).'</pre>'),'');
            //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__.' CHARACTER_MAXIMUM_LENGTH<br><pre>'.print_r($field->CHARACTER_MAXIMUM_LENGTH,true).'</pre>'),'');
            
            switch ($field->DATA_TYPE)
            {
                case 'varchar':
                $form->setFieldAttribute($field->COLUMN_NAME, 'size', $field->CHARACTER_MAXIMUM_LENGTH);
                break;
            }
            
           } 
           
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' form<br><pre>'.print_r($form,true).'</pre>'),'');
        
		return $form;
	}
    
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_sportsmanagement/models/forms/sportsmanagement.js';
	}
    
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
	   $app = JFactory::getApplication();
       
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sportsmanagement.edit.statistic.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
       
        
		return $data;
	}
	
	/**
	 * Method to save item order
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($pks = NULL, $order = NULL)
	{
		$row =& $this->getTable();
		
		// update ordering values
		for ($i=0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i])
			{
				$row->ordering=$order[$i];
				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}
    
    /**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
	   $app = JFactory::getApplication();
       $date = JFactory::getDate();
	   $user = JFactory::getUser();
       $post = JRequest::get('post');
       // Set the values
	   $data['modified'] = $date->toSql();
	   $data['modified_by'] = $user->get('id');
       
       //$app->enqueueMessage(JText::_('sportsmanagementModelstatistic save<br><pre>'.print_r($data,true).'</pre>'),'Notice');
       //$app->enqueueMessage(JText::_('sportsmanagementModelstatistic post<br><pre>'.print_r($post,true).'</pre>'),'Notice');
       
       if (isset($post['extended']) && is_array($post['extended'])) 
		{
			// Convert the extended field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($post['extended']);
			$data['extended'] = (string)$parameter;
		}
        
        if (isset($post['params']) && is_array($post['params'])) 
		{
			// Convert the extended field to a string.
            //$paramsString = json_encode( $post['params'] );
            //$data['params'] = $paramsString;
            
			$parameter = new JRegistry;
			$parameter->loadArray($post['params']);
			$data['params'] = (string)$parameter;
            
            
		}
        
        //$app->enqueueMessage(JText::_('sportsmanagementModelstatistic save data<br><pre>'.print_r($data,true).'</pre>'),'Notice');
        
        // Proceed with the save
		return parent::save($data);   
    }
    
    
}
