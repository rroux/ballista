<div class="deployHistory">
  <?php
  if ($historylog) {
    echo '<div class="historyTitle">' . $this->Html->image('toggle_plus.png', array('id' => 'toggle', 'align' => 'left')) . 'Deployment history of ' . $branch . ' branch &#187;</div>';
    echo '<table id="historyTable' . ($serverkey + 1) . '">';
    echo '<tr>';
    echo '<th>Commit</th>';
    echo '<th>User</th>';
    echo '<th>Logtime</th>';
    echo '<th>Comment</th>';
    echo '<th>Status</th>';
    echo '</tr>';
    foreach ($historylog as $history) {
      echo '<tr>';
      echo '<td>' . substr($history['Log']['commit'], 0, 7) . '</td>';
      echo '<td>' . $history['User']['username'] . '</td>';
      echo '<td>' . date("d/m H:i", strtotime($history['Log']['updated'])) . '</td>';
      echo '<td>' . substr($history['Log']['comment'], 0, 40) . '</td>';
      echo '<td>' . $this->element('status_icons', array('status' => $history['Log']['status'])) . '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }
?>
</div>
