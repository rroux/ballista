<?php
  // Generate the ACL
  $acl = $this->Session->read('User.permissions');
?>
<script type="text/javascript">
function tog(name, t) {
  $('#' + name).toggle();
  $(t).toggleClass('minus');
}
</script>


<div class="projects index">
  <?php
  $i = 0;
  $last_tag = null;
  foreach ($projects as $project):
    $class = null;
    if ($i++ % 2 == 0) {
      $class = ' class="altrow"';
    }
    
    $tag = $project['Tag']['tag'];
    if (empty($tag)) {
      $tag = 'Untagged';
    }
    $tag_class = str_replace(' ', '', $tag);
    if ($last_tag !== $tag):
      if ($last_tag !== ''):
        echo '</table>';
      endif;
      $last_tag = $tag;
?>
  
    <h2 id="<?php echo $tag_class; ?>Legend" class="projects plus" onclick="tog('<?php echo $tag_class?>Table', this)"><?php echo $tag ?></h2>
    
    <table id="<?php echo $tag_class?>Table" class="collapsed" cellpadding="0" cellspacing="0">
    <tr>
      <th width="30"><?php echo $this->Paginator->sort('id');?></th>
      <th width="300"><?php echo $this->Paginator->sort('name');?></th>
      <th width="400"><?php echo $this->Paginator->sort('description');?></th>
      <th><?php echo $this->Paginator->sort('active');?></th>
      <th>Activity log</th>
      <th>Deploy</th>
      <th>&nbsp;</th>
    </tr>
  <?php
  endif;
  ?>
  <tr<?php echo $class;?>>
    <td><?php echo $project['Project']['id']; ?>&nbsp;</td>
    <td>
    <?php 
      if ($project['Project']['active'] == 1 && !empty($acl[$project['Project']['id']])) { 
          echo $this->Html->link(
            $project['Project']['name'],
            array('controller' => 'instances', 'action' => 'deploy', $project['Project']['id'], Configure::read('Ballista.master')),
            array('escape' => false)
          ); 
      } else {
        echo $project['Project']['name'];
      }
    ?>&nbsp;
    </td>
    <td><?php echo $project['Project']['description']; ?>&nbsp;</td>
    <?php 
        echo '<td>';
        if ($this->Session->read('User.admin') == 1) {
           echo $project['Project']['active'] == 1 ? $this->Html->link($this->Html->image('active.png', array('title' => 'Active')), array('action' => 'status', $project['Project']['id'], $project['Project']['active']), array('escape' => false)) : $this->Html->link($this->Html->image('inactive.png', array('title' => 'Inactive')), array('action' => 'status', $project['Project']['id'], $project['Project']['active']), array('escape' => false));
        }else{
           echo $project['Project']['active'] == 1 ? $this->Html->image('active.png', array('title' => 'Active')) : $this->Html->image('inactive.png', array('title' => 'Inactive'));
        }
        echo '</td>';

        echo '<td>';
        echo $this->Html->link(
          $this->Html->image('activity_log.png', array('alt' => 'View', 'title' => 'View')), 
          array('controller' => 'logs', 'action' => 'index', $project['Project']['id']),
          array('escape' => false)
        );
        echo '</td>';

        if($project['Project']['active'] == 1 && !empty($acl[$project['Project']['id']])){
          echo '<td>';
          echo $this->Html->link(
            $this->Html->image('deploy.png', array('alt' => 'Deploy', 'title' => 'Deploy')), 
            array('controller' => 'instances', 'action' => 'deploy', $project['Project']['id'], Configure::read('Ballista.master')),
            array('escape' => false)
          );
          echo '</td>';

        }else{
          echo '<td>&nbsp;</td>';
        }

        echo '<td class="buttons">';
        echo $this->Html->link(
          $this->Html->image('view.png', array('alt' => 'View', 'title' => 'View')), 
          array('action' => 'view', $project['Project']['id']),
          array('escape' => false)
        );

        if ($this->Session->read('User.admin') == 1) { 
          echo $this->Html->link(
            $this->Html->image('edit.png', array('alt' => 'Edit', 'title' => 'Edit')), 
            array('action' => 'edit', $project['Project']['id']),
            array('escape' => false)
          );
        }

        if ($this->Session->read('User.admin') == 1) { 
          echo $this->Html->link(
            $this->Html->image('delete.png', array('alt' => 'Delete', 'title' => 'Delete')), 
            array('action' => 'delete', $project['Project']['id']), 
            array('escape' => false), 
            sprintf(__('Are you sure you want to delete project %s?', true), $project['Project']['name'])
          );
        }
        echo '</td>';
      ?>
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

<?php echo $this->element('stickyhead') ?>
