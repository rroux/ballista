<div class="instances form">
  <?php echo $this->Form->create('Instance'); ?>
  <fieldset>
    <legend>
      <?php __('Edit Instance'); ?>
    </legend>
    <?php
      echo $this->Form->input('id');
      echo $this->Form->input('project_id');
      echo $this->Form->input('server_id');
      echo $this->Form->input('Group');
    ?>
  </fieldset>
  <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
<?php echo $this->element('actions'); ?>
