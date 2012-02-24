<div class="groups view">
<h2><?php  __('Group');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Group'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['group']; ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php echo $this->element('actions') ?>

<div class="related">
	<h3><?php __('Related Instances');?></h3>
	<?php if (!empty($group['Instance'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
	  <th>Id</th>
    <th>Project Id</th>
    <th>Server Id</th>
	</tr>
	<?php
		$i = 0;
		foreach ($group['Instance'] as $instance):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $instance['id'];?></td>
			<td><?php echo $instance['project_id'];?></td>
			<td><?php echo $instance['server_id'];?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>

<div class="related">
	<h3><?php __('Related Users');?></h3>
	<?php if (!empty($group['User'])):?>
	<table cellpadding = "0" cellspacing = "0">
  <tr>
    <th>Id</th>
    <th>Username</th>
    <th>First name</th>
    <th>Last name</th>
    <th>Email</th>
    <th>Active</th>
  </tr>
	<?php
		$i = 0;
		foreach ($group['User'] as $user):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $user['id'];?></td>
			<td><?php echo $user['username'];?></td>
			<td><?php echo $user['firstname'];?></td>
			<td><?php echo $user['lastname'];?></td>
			<td><?php echo $user['email'];?></td>
			<td><?php echo $user['active'] == 1 ? $this->Html->image('active.png') : $this->Html->image('inactive.png'); ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>
