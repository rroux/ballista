<div class="projects form">
<?php echo $this->Form->create('Project');?>
  <fieldset>
     <legend><?php __('Edit Project'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('description');
    echo $this->Form->input('host', array('options' => $hosts));
    echo $this->Form->input('path', array('label' => 'Path/URL to the project repository'));
    echo $this->Form->input('active');

    foreach ($servers as $serverid => $hostname) {
      $checked = (isset($instances) && $instances[$serverid]['active']) == 1 ? 'checked' : '';
      echo '<fieldset>';
      echo $this->Form->input('path', array('name' => 'data[Server]['.$serverid.'][path]', 'label' => 'Path to the project on ' . $hostname, 'value' => isset($instances) ? $instances[$serverid]['path'] : '' ));
      echo $this->Form->input('Active', array('type' => 'checkbox', 'name' => 'data[Server]['.$serverid.'][active]', 'label' => 'Allow deploy on ' . $hostname, 'value' => 1, 'checked' => $checked));
      echo '</fieldset>';
    }

    echo '<h3>Notifications</h3>';
    echo '<fieldset>';
    echo $this->Form->input('notify', array('label' => 'Email addresses to receive deploy notifications (comma separated)'));
    echo $this->Form->input('message', array('type' => 'textarea', 'label' => 'Notification message'));
    echo '</fieldset>';

  ?>
  </fieldset>
  <?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php echo $this->element('actions') ?>
