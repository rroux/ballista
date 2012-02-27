<div class="projects view">
<h2><?php  __('Project');?></h2>
  <dl><?php $i = 0; $class = ' class="altrow"';?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['id']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['name']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['description']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Path'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['path']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Host'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['host']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Notification Receivers'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['notify']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Notification Message'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $project['Project']['message']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php 
        echo $project['Project']['active'] == 1 ? $this->Html->image('active.png') : $this->Html->image('inactive.png'); 
      ?>
      &nbsp;
    </dd>
  </dl>
</div>

<?php echo $this->element('actions') ?>

<div class="related">
  <h3><?php __('Instances');?></h3>
  <?php if (!empty($project['Server'])):?>
  <table cellpadding = "0" cellspacing = "0">
  <tr>
    <th><?php __('Server'); ?></th>
    <th><?php __('Hostname'); ?></th>
    <th><?php __('Path'); ?></th>
    <th><?php __('Active'); ?></th>
  </tr>
  <?php
    $i = 0;
    foreach ($project['Server'] as $server):
      $class = null;
      if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
      }
    ?>
    <tr<?php echo $class;?>>
      <td><?php echo $server['server'];?></td>
      <td><?php echo $server['hostname'];?></td>
      <td><?php echo $server['Instance']['path'];?></td>
      <td><?php echo $server['Instance']['active'] == 1 ? $this->Html->image('active.png') : $this->Html->image('inactive.png'); ?></td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php endif; ?>
</div>
