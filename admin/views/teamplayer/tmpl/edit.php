<?php 
defined('_JEXEC') or die('Restricted access');


?>
<!-- import the functions to move the events between selection lists	-->
<?php
//$version = urlencode(JoomleagueHelper::getVersion());
//echo JHTML::script( 'JL_eventsediting.js?v='.$version, 'administrator/components/com_joomleague/assets/js/' );
?>

<form action="<?php echo JRoute::_('index.php?option=com_sportsmanagement&layout=edit&id='.(int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm">
	<div class="col50">

<?php
echo JHTML::_('tabs.start','tabs', array('useCookie'=>1));
echo JHTML::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_DETAILS'), 'panel1');
echo $this->loadTemplate('details');

echo JHTML::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_PICTURE'), 'panel2');
echo $this->loadTemplate('picture');

echo JHTML::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_DESCRIPTION'), 'panel3');
echo $this->loadTemplate('description');

echo JHTML::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_EXTENDED'), 'panel3');
echo $this->loadTemplate('extended');

echo JHTML::_('tabs.end');
?>

		<input type="hidden" name="eventschanges_check"	id="eventschanges_check" value="0" />
		
		<input type="hidden" name="team_id"				value="<?php echo $this->teamws->team_id; ?>" />
		<input type="hidden" name="id"				value="<?php echo $this->project_player->id; ?>" />
		<input type="hidden" name="task"				value="teamplayer.edit" />
	</div>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>