<?php 
defined('_JEXEC') or die('Restricted access');

//JHTMLBehavior::formvalidation();
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');

$i    = 1;
?>

<form action="<?php echo JRoute::_('index.php?option=com_sportsmanagement&view=template&layout=edit&id='.(int) $this->template->id); ?>" method="post" id="adminForm" name="adminForm" >
	<div style='text-align: right;'>
		<?php echo $this->lists['templates']; ?>
	</div>
	<?php
	if ($this->project->id != $this->template->project_id) {
		JError::raiseNotice(0, JText::_('COM_SPORTSMANAGEMENT_ADMIN_TEMPLATE_MASTER_WARNING'));
		?><input type="hidden" name="master_id" value="<?php echo $this->template->project_id; ?>"/><?php
	}
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::sprintf('COM_SPORTSMANAGEMENT_ADMIN_TEMPLATE_LEGEND', '<i>' . JText::_('COM_SPORTSMANAGEMENT_FES_' . strtoupper($this->form->getName()) . '_NAME') . '</i>', '<i>' . $this->project->name . '</i>'); ?></legend>
		<fieldset class="adminform">
			<?php
			echo JText::_('COM_SPORTSMANAGEMENT_FES_' . strtoupper($this->form->getName()) . '_DESCR');
			?>
		</fieldset>

		<?php
		echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
        $fieldSets = $this->form->getFieldsets();
        foreach ($fieldSets as $name => $fieldSet) :
            $label = $fieldSet->name;
            echo JHtml::_('tabs.panel',JText::_($label), 'panel'.$i++);
			?>
			<fieldset class="panelform">
				<?php
				if (isset($fieldSet->description) && !empty($fieldSet->description)) :
					echo '<fieldset class="adminform">'.JText::_($fieldSet->description).'</fieldset>';
				endif;
				?>
				<ul class="config-option-list">
				<?php foreach ($this->form->getFieldset($name) as $field): ?>
					<li>
					<?php if (!$field->hidden) : ?>
					<?php echo $field->label; ?>
					<?php endif; ?>
					<?php echo $field->input; ?>
					</li>
				<?php endforeach; ?>
				</ul>
			</fieldset>
 
    <div class="clr"></div>
    <?php endforeach; ?>
    <?php echo JHtml::_('tabs.end'); ?>
	<div>		
		<input type='hidden' name='user_id' value='<?php echo $this->user->id; ?>'/>
		
		<input type="hidden" name="task" value="template.edit"/>
        
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>