 <div id="flashHolder"><?php echo $session->flash() ?></div>

<div class="tags form">
<?php echo $this->Form->create('Tag');?>
  <fieldset>
     <legend><?php 
     $title = ucfirst(h($this->action));
     __($title . ' Tag'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('tag');
  ?>
  </fieldset>
  <?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php echo $this->element('actions') ?>
