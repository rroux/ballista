<?php
  if ($commitlog) {
    echo $this->Git->formatCommits($commitlog, $instance['Project']['name'], $instance['Project']['host'], $serverkey, $lastcommit['Log']['commit'], $limit);
  } else {
    echo '<div class="infobox">No commits found!.</div>';
  }

  echo $this->element('deploy_history', array(
        'historylog' => $instance['history'], 
        'branch' => $branch, 
        'serverkey' => $serverkey) 
  );

?>
