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

jimport('joomla.application.component.view');

/**
 * sportsmanagementViewallprojects
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewallprojects extends JViewLegacy
{
    protected $state = null;
	protected $item = null;
	protected $items = null;
	protected $pagination = null;
    
	/**
	 * sportsmanagementViewallprojects::display()
	 * 
	 * @param mixed $tpl
	 * @return void
	 */
	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();
        $option = JRequest::getCmd('option');
		// Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        
        
		$user		= JFactory::getUser();
        $starttime = microtime(); 
        $inputappend = '';
        $this->tableclass = $jinput->getVar('table_class', 'table','request','string');

		$state 		= $this->get('State');
		$items 		= $this->get('Items');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
		$pagination	= $this->get('Pagination');
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' ' .  ' state<br><pre>'.print_r($state,true).'</pre>'),'');
		
        //build the html options for nation
		$temp[] = JHtml::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_COUNTRY'));
		if ($res = JSMCountries::getCountryOptions())
        {
            $temp = array_merge($temp,$res);
            }
		
        //$lists['nation'] = $temp;
        $lists['nation2'] = JHtmlSelect::genericlist(	$temp,
																'filter_search_nation',
																$inputappend.'class="inputbox" style="width:140px; " onchange="this.form.submit();"',
																'value',
																'text',
																$state->get('filter.search_nation'));
                                                                
        unset($temp);
        
        $temp[] = JHtml::_('select.option','',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_LEAGUES'),'id','name' );
		if ($res = sportsmanagementModelLeagues::getLeagues())
        {
            $temp = array_merge($temp,$res);
            }
		
        //$lists['nation'] = $temp;
        $lists['leagues'] = JHtmlSelect::genericlist(	$temp,
																'filter_search_leagues',
																$inputappend.'class="inputbox" style="width:140px; " onchange="this.form.submit();"',
																'id',
																'name',
																$state->get('filter.search_leagues'));
                                                                
        unset($temp);
        
        $temp[] = JHtml::_('select.option','',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_SEASONS'),'id','name' );
		if ($res = sportsmanagementModelSeasons::getSeasons())
        {
            $temp = array_merge($temp,$res);
            }
		
        //$lists['nation'] = $temp;
        $lists['seasons'] = JHtmlSelect::genericlist(	$temp,
																'filter_search_seasons',
																$inputappend.'class="inputbox" style="width:140px; " onchange="this.form.submit();"',
																'id',
																'name',
																$state->get('filter.search_seasons'));
                                                                
        unset($temp);
        
        // Set page title
		$document->setTitle(JText::_('COM_SPORTSMANAGEMENT_ALLPROJECTS_PAGE_TITLE'));
        
        $form = new stdClass();
        $form->limitField = $pagination->getLimitBox();
        
        $this->filter = $state->get('filter.search');
               
      
		$this->assignRef('form', $form);
		$this->assignRef('items', $items);
		$this->assignRef('state', $state);
		$this->assignRef('user', $user);
		$this->assignRef('pagination', $pagination);
        
        $this->sortDirection    = $this->state->get('filter_order_Dir');
        $this->sortColumn       = $this->state->get('filter_order');
        
        $this->assignRef('lists', $lists);

		parent::display($tpl);
	}

}
?>