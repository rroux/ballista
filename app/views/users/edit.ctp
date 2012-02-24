<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
    if($this->action == 'edit'){
  		echo $this->Form->input('password', array('after' => 'Leave this field empty if you do not wish to change your password'));
    }else{
      echo $this->Form->input('password');
    }
		echo $this->Form->input('firstname');
		echo $this->Form->input('lastname');
		echo $this->Form->input('email');
		echo $this->Form->input('active');
    if ($this->Session->read('User.admin') == 1) {
		  echo $this->Form->input('Group');
    }
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php echo $this->element('actions') ?>



