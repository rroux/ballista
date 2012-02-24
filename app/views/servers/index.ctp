<div class="servers index">
	<h2><?php __('Servers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('server');?></th>
			<th><?php echo $this->Paginator->sort('hostname');?></th>
			<th class="actions">&nbsp;</th>
	</tr>
	<?php
	$i = 0;
	foreach ($servers as $server):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $server['Server']['id']; ?>&nbsp;</td>
    <td><?php echo $server['Server']['server']; ?>&nbsp;</td>
		<td><?php echo $server['Server']['hostname']; ?>&nbsp;</td>
		<td class="buttons">
      <?php 
        echo $this->Html->link(
          $this->Html->image('view.png', array('alt' => 'View', 'title' => 'View')), 
          array('action' => 'view', $server['Server']['id']),
          array('escape' => false)
        );
        echo $this->Html->link(
          $this->Html->image('edit.png', array('alt' => 'Edit', 'title' => 'Edit')),  
          array('action' => 'edit', $server['Server']['id']),
          array('escape' => false)
        );
        echo $this->Html->link(
          $this->Html->image('delete.png', array('alt' => 'Delete', 'title' => 'Delete')), 
          array('action' => 'delete', $server['Server']['id']), 
          array('escape' => false), 
          sprintf(__('Are you sure you want to delete %s?', true), $server['Server']['server'])
        ); 
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
