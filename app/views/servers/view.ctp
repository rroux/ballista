<div class="servers view">
<h2><?php  __('Server');?></h2>
  <dl><?php $i = 0; $class = ' class="altrow"';?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $server['Server']['id']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hostname'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $server['Server']['server']; ?>
      &nbsp;
    </dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ipaddress'); ?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>>
      <?php echo $server['Server']['hostname']; ?>
      &nbsp;
    </dd>
  </dl>
</div>

<?php echo $this->element('actions') ?>

<div class="related">
  <h3><?php __('Related Projects');?></h3>
  <?php if (!empty($server['Project'])):?>
  <table cellpadding = "0" cellspacing = "0">
  <tr>
    <th><?php __('Id'); ?></th>
    <th><?php __('Name'); ?></th>
    <th><?php __('Description'); ?></th>
    <th><?php __('Path'); ?></th>
    <th><?php __('Active'); ?></th>
  </tr>
  <?php
    $i = 0;
    foreach ($server['Project'] as $project):
      $class = null;
      if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
      }
    ?>
    <tr<?php echo $class;?>>
      <td><?php echo $project['id'];?></td>
      <td><?php echo $project['name'];?></td>
      <td><?php echo $project['description'];?></td>
      <td><?php echo $project['path'];?></td>
      <td><?php echo $project['active'] == 1 ? $this->Html->image('active.png') : $this->Html->image('inactive.png'); ?></td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php endif; ?>

</div>
