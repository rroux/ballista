<?php 
  if ($status == 'Running') {  
    echo '<div class="running">'.$this->Html->image('ajax-loader.gif', array('title' => 'Processing..', 'alt' => 'Processing...')).'</div>';
  } elseif ($status == 'Upcoming') {
    echo $this->Html->image('clock.png', array('title' => 'Deploy in the next cron job interval', 'alt' => 'Deploy in future'));
  } elseif ($status == 'Failed') {
    echo $this->Html->image('fail.png', array('title' => 'Failed', 'alt' => 'Failed'));
  } elseif ($status == 'Completed') {
    echo $this->Html->image('tick.png', array('title' => 'Completed', 'alt' => 'Completed'));
  }
?>