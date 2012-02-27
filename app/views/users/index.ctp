<div class="users index">
  <h2><?php __('Users');?></h2>
  <table cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $this->Paginator->sort('id');?></th>
    <th><?php echo $this->Paginator->sort('username');?></th>
    <th><?php echo $this->Paginator->sort('firstname');?></th>
    <th><?php echo $this->Paginator->sort('lastname');?></th>
    <th><?php echo $this->Paginator->sort('email');?></th>
    <th><?php echo $this->Paginator->sort('active');?></th>
    <th class="actions">&nbsp;</th>
  </tr>
  <?php
    $i = 0;
    foreach ($users as $user):
      $class = null;
      if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
      }
  ?>
  <tr<?php echo $class;?>>
    <td><?php echo $user['User']['id']; ?>&nbsp;</td>
    <td><?php echo $user['User']['username']; ?>&nbsp;</td>
    <td><?php echo $user['User']['firstname']; ?>&nbsp;</td>
    <td><?php echo $user['User']['lastname']; ?>&nbsp;</td>
    <td><?php echo $user['User']['email']; ?>&nbsp;</td>
    <td>
    <?php
      if ($this->Session->read('User.admin') == 1) {
        echo $user['User']['active'] == 1 ? $this->Html->link($this->Html->image('active.png'), array('action' => 'status', $user['User']['id'], $user['User']['active']), array('escape' => false)) : $this->Html->link($this->Html->image('inactive.png'), array('action' => 'status', $user['User']['id'], $user['User']['active']), array('escape' => false));  
      }else{
        echo $user['User']['active'] == 1 ? $this->Html->image('active.png') : $this->Html->image('inactive.png');
      }
    ?>
    </td>
    <td class="buttons">
      <?php 
        if ($this->Session->read('User.id') == $user['User']['id'] || $this->Session->read('User.admin') == 1) { 
          echo $this->Html->link(
            $this->Html->image('view.png', array('alt' => 'View', 'title' => 'View')), 
            array('action' => 'view', $user['User']['id']), 
            array('escape' => false)
          );        
          echo $this->Html->link(
            $this->Html->image('edit.png', array('alt' => 'Edit', 'title' => 'Edit')), 
            array('action' => 'edit', $user['User']['id']), 
            array('escape' => false)
          ); 
        }
        if ($this->Session->read('User.admin') == 1) { 
          echo $this->Html->link(
            $this->Html->image('delete.png', array('alt' => 'Delete', 'title' => 'Delete')), 
            array('action' => 'delete', $user['User']['id']), 
            array('escape' => false), 
            sprintf(__('Are you sure you want to delete %s?', true), $user['User']['username'])
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
  ?>
  </p>

  <div class="paging">
    <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
    |
    <?php echo $this->Paginator->numbers();?>
    |
    <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
  </div>
</div>

<?php echo $this->element('actions') ?>
