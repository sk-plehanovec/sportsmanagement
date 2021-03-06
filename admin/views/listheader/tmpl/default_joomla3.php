<?php 
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie können es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder späteren
* veröffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es nützlich sein wird, aber
* OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

defined('_JEXEC') or die('Restricted access');
$view = JRequest::getCmd('view', 'cpanel');


$buttons = array(
					array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=sportstypes'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_SPORTSTYPES'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),
                    array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=seasons'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_SEASONS'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),
                    array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=leagues'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_LEAGUES'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),      
                    array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=jlextfederations'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_FEDERATIONS'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),    
                    array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=jlextcountries'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_COUNTRIES'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),    
                    array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=jlextassociations'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_ASSOCIATIONS'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),    
                     array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=positions'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_POSITIONS'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),
                      array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=eventtypes'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_EVENTS'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						),
                      array(
						'link' => JRoute::_('index.php?option=com_sportsmanagement&view=agegroups'),
						'image' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'icon' => 'com_sportsmanagement/assets/icons/transparent_schrift_48.png',
						'text' => JText::_('COM_SPORTSMANAGEMENT_D_MENU_AGEGROUPS'),
						'access' => array('core.manage', 'com_sportsmanagement'),
						'group' => 'COM_SPORTSMANAGEMENT_D_HEADING_BASIS_DATA'
						)          
                        );

$groupedButtons = array();

//echo ' <br><pre>'.print_r($buttons,true).'</pre>';

		foreach ($buttons as $button)
		{
			$groupedButtons[$button['group']][] = $button;
		}
        
$html = JHtml::_('links.linksgroups', $groupedButtons);
        
?>
<?php if (!empty( $this->sidebar)) : ?>

    <div id="j-sidebar-container" class="span2">
<div class="sidebar-nav quick-icons">
		<?php echo $html;?>
</div>
        <?php echo $this->sidebar; ?>

	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

<?PHP
if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
{
echo $this->loadTemplate('debug');
}
?>

    
<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_WEBLINKS_SEARCH_IN_TITLE'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			           
            </div>
            	<?php
                $startRange = JComponentHelper::getParams(JRequest::getCmd('option'))->get('character_filter_start_hex', '0');
		$endRange = JComponentHelper::getParams(JRequest::getCmd('option'))->get('character_filter_end_hex', '0');
   
		for ($i=$startRange; $i <= $endRange; $i++)
		{
			
            //printf("<a href=\"javascript:searchPerson('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;",chr($i),chr($i));
            printf("<a href=\"javascript:searchPerson('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;",'&#'.$i.';','&#'.$i.';');
			}
                
				?>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			
			
		</div>
        