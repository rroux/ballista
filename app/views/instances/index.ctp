<div class="instances index">
	<h2><?php __('Permissions');?></h2>

  <table cellspacing="0" cellpadding="0">
    <tr>
      <th>Projects</th>
      <?php 
        foreach ($servers as $server) {
          echo '<th>'.$server['Server']['server'].'</th>';
        } 
      ?>    
    </tr>
    
    <?php
      foreach ($projects as $project) {
        echo '<tr>';
        echo '<td><b>'.$project['Project']['name'].'</b></td>';
        
        $project_id = $project['Project']['id'];
        foreach ($servers as $server) {
          echo '<td>';
          $server_id = $server['Server']['id'];
          foreach ($instances as $instance) {
            if($instance['Instance']['project_id'] == $project_id && $instance['Instance']['server_id'] == $server_id){
              echo $this->Html->link(
                $this->Html->image('edit.png', array('alt' => 'Edit', 'title' => 'Edit', 'align' => 'right', 'class' => 'instanceEdit')), 
                array('action' => 'edit', $instance['Instance']['id']),
                array('escape' => false)
              );
              foreach($instance['Group'] as $groups){
                echo $groups['group'] . '<br/>';
              }
            }
          }
          echo '</td>';
        }
        echo '</tr>';
      }
    ?>
  </table>
</div>

<?php echo $this->element('actions') ?>

<?php echo $this->element('stickyhead') ?>