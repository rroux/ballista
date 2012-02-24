<div class="servers form">
<?php echo $this->Form->create('Server');?>
	<fieldset>
 		<legend><?php __('Add/Edit Server'); ?></legend>
	<?php
		echo $this->Form->input('server', array('label' => 'Server name'));
		echo $this->Form->input('hostname', array('label' => 'Hostname or IP address of the server'));
    echo $this->Form->input('branches', array('label' => 'Deploy branches on this server?'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php echo $this->element('actions') ?>

