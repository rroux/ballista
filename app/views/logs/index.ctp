<div class="logs index">
	<h2><?php __('Logs');?><?php if (!empty($this->params['pass'][0])) { echo ': '.$projects[$this->params['pass'][0]]; } ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Project</th>
			<th><?php echo $this->Paginator->sort('branch');?></th>
			<th><?php echo $this->Paginator->sort('Server', 'instance_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('logtime');?></th>
			<th>Comment</th>
			<th>Commit</th>
			<th>Status</th>
      <th>Log</th>
			<th class="actions">&nbsp;</th>
	</tr>
	<?php
	$i = 0;
	foreach ($actionlogs as $log):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $projects[$log['Instance']['project_id']] ?></td>
		<td><?php echo $log['Log']['branch'] ?></td>
		<td><?php echo $servers[$log['Instance']['server_id']] ?></td>
		<td><?php echo $log['User']['username'] ?></td>
		<td><?php echo '<span title="'.$log['Log']['logtime'].'">' . date("d/m H:i", strtotime($log['Log']['logtime'])) . '</span>'; ?></td>
		<td><?php echo $log['Log']['comment']; ?>
		  <div id="logOutput_<?php echo $log['Log']['id']; ?>" class="lightBox">
		    <div class="closeButton"><?php echo $this->Html->image('cancel.png'); ?></div>
		    <div class="text"><?php echo str_replace("\n", '<br/>', $log['Log']['output']); ?></div>
		  </div>
		  &nbsp;
		</td>
		<td>
		  <?php 
		    $short_commit = substr($log['Log']['commit'], 0, 7);
        echo '<span title="'.$log['Log']['commit'].'">'.$short_commit.'</span>'; 
      ?>
		</td>
		<td id="statuscell_<?php echo $log['Log']['id']; ?>">
		  <?php echo $this->element('status_icons', array('status' => $log['Log']['status'])); ?>
		</td>
		<td>
		  <?php 
        if ($log['Log']['status'] == 'Completed' || $log['Log']['status'] == 'Failed') {
		      echo $this->Html->image('log.png', array('title' => 'View log', 'alt' => 'View log', 'class' => 'logWindow', 'id' => 'Output_'.$log['Log']['id']));
		    } 
		  ?>
		</td>
		<td>
			<?php
			  $today = date('Y-m-d H:i:s');
			  //Can delete only if log is in future and log belongs to the viewing user or if admin
			  if(($log['Log']['logtime'] > $today) && ($this->Session->read('User.admin') == 1 || $this->Session->read('User.id') == $log['Log']['user_id'])){
  			  echo $this->Html->link(
  			    $this->Html->image('delete.png', array('alt' => 'Cancel deploy', 'title' => 'Cancel deploy')),
  			    array('action' => 'delete', $log['Log']['id']),
  			    array('escape' => false), 
  			    sprintf(__('Are you sure you want to cancel this deploy?', true), $log['Log']['id'])
          );
			  }
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>

<?php echo $this->element('actions') ?>

<?php echo $this->element('logjs') ?>
