<?php 
  if (!empty($instances)) {
    echo $this->Form->create(null, array(
      'url' => array(
        'controller' => 'logs', 
        'action' => 'execute', 
        $this->params['pass'][0]
      )
    )); 

    echo '<div class="actionBox">';
    echo $this->Form->input('allservers', array('type' => 'checkbox', 'label' => 'All servers', 'id' => 'allservers'));
    echo $this->Form->input('data[Comment]', array('label' => '', 'name' => 'data[Comment]', 'default' => 'deploy comment'));
    echo $this->Form->submit('Deploy ' . $project['Project']['name']);

    echo $this->Form->input('sendmail', array('type' => 'checkbox', 'label' => 'Send mail notification', 'id' => 'sendmail', 'div' => 'mailoption'));
    echo '<div id="mailBox">';
    echo $this->Form->input('notify', array('label' => 'To', 'name' => 'data[Notification][notify]'));
    echo $this->Form->input('message', array('type' => 'textarea', 'name' => 'data[Notification][message]'));
    echo '</div>';

    echo '</div>';
?>

<table id="instancesTable">
  <tr>
  <?php 
    $row = 0;
    foreach($instances as $key => $instance){
      $row++; 
  ?>
    <td class="instanceHolder box<?php echo $row ?>">
      <div class="serverBox">
	      <?php 
	        echo $this->Form->input($instance['Server']['server'], array('type' => 'checkbox', 'name' => 'data[Log]['.$key.'][instance_id]', 'value' => $instance['Instance']['id'])); 
	        echo $this->Form->input('user_id_'.$instance['Server']['server'], array('type' => 'hidden', 'name' => 'data[Log]['.$key.'][user_id]', 'value' => $this->Session->read('User.id')));
          echo $this->Form->input('repo_'.$instance['Server']['server'], array('type' => 'hidden', 'name' => 'data[Path]', 'value' => $instance['Project']['path']));
          echo '<div class="select text"><label>Branch:</label><select id="branch'.$key.'" class="branchfield" name="data[Log]['.$key.'][branch]">';
          foreach ($instance['branches'] as $branch) {
            $branch = str_replace(array(' ', '*'), '', $branch);
            echo '<option value="'.$branch.'">' . $branch . '</option>';
          }
          echo '</select></div>';
	        echo $this->Form->input('logtime_'.$instance['Server']['server'], array('type' => 'text', 'name' => 'data[Log]['.$key.'][logtime]', 'value' => date('Y-m-d H:i'), 'label' => 'Deploy at:', 'class' => 'timefield'));
	      ?>
      </div>
      <script type="text/javascript">
        //<![CDATA[
          $('#branch<?php echo $key; ?>').change(function() {
        	  $.ajax({
        	    url: '<?php echo $this->webroot ?>instances/branch_commits/<?php echo $instance["Instance"]["id"]; ?>/' + $(this).val() + '/' + <?php echo $key; ?> + '/<?php echo $limit; ?>', 
        	    beforeSend: function() {$('.box<?php echo $row ?> .commitInfo').html('<?php echo $this->Html->image("ajax-loader.gif"); ?>')},
        	    success: function(data){
        	      $('.box<?php echo $row ?> .commitInfo').html(data);
        	      firstRadioOn();
        	    }
        	  });
          });
        //]]>
      </script>
	    <div class="commitInfo">
        <?php 
          if ($instance['commits']) {
            echo $this->Git->formatCommits($instance['commits'], $project['Project']['name'], $project['Project']['host'], $key, $instance['lastcommit']['Log']['commit'], $limit);
          } else {
            echo '<div class="infobox">No commits found!.</div>';
          }
	      ?>
	      
        <?php echo $this->element('deploy_history', array(
                'historylog' => $instance['history'], 
                'branch' => $this->params['pass'][1], 
                'serverkey' => $key)
              ); 
        ?>
	    </div>
    </td>
    <td class="cellspace">&nbsp;</td>
  <?php } ?>
  </tr>
</table>

<?php
    echo $this->Form->end();
    
    echo $this->Html->link('Show more commits', array('action' => 'deploy', $this->params['pass'][0], $this->params['pass'][1], $limit + 3));
  } else {
    echo '<h2>Error</h2><p class="error">No servers to deploy to. Contact the administrator to setup instances for this project to be deployed to servers.</p>';
  }

  // Javascript for deploy
  echo $this->element('deployjs');

?>
