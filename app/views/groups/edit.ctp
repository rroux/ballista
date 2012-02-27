<div class="groups form">
  <?php echo $this->Form->create('Group');?>
  <fieldset>
    <legend>
      <?php __('Add/Edit Group');?>
    </legend>
    <?php echo $this->Form->input('group'); ?>
  </fieldset>
  <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
<?php echo $this->element('actions'); ?>
