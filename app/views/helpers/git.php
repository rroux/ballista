<?php
/**
 * Git Helper
 *
 * This helper class contains methods to help format git commits for neat display
 *
 * Ballista : Code Deployment System
 * Copyright 2011-2012, Aller Internett AS <it@allerinternett.no>
 *
 * This file is part of Ballista.
 *
 * Ballista is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ballista is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ballista.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright     Copyright 2011-2012, Aller Internett AS <it@allerinternett.no>
 * @author        Baheerathan Vykundanathan <thamba@allerinternett.no>
 * @package       Ballista
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.txt)
 */

class gitHelper extends AppHelper {

  var $helpers = array('Html');

  /**
   * Takes the log output and formats it neatly.
   * Displays the last few commits previously deloyed to the server and
   * all new commits yet to be deployed.
   *
   * @param   array   $log        Log of commits
   * @param   string  $project    Name of project
   * @param   string  $host       Type of host (Git/Local)
   * @param   integer $serverkey  Index of the server array
   * @param   string  $lastcommit Last commit hash
   * @param   integer $limit      Number of commits to show
   *
   * @return  string  $output     Neatly formatted commits
   */
  function formatCommits($log, $project, $host, $serverkey, $lastcommit, $limit = 3) {
    $class = 'new';
    $count = 0;
    $commits = array();

    if ($host == 'Github') {// Github log list formatting
      foreach ($log as $index => $item) {
        $item['class'] = $class;
        // Check if this is the last commit deployed
        if ($lastcommit == $item['id']) {
          $item['class'] = $class = 'old';
        }
        // If old commits are being displayed, then increment counter and stop at $limit
        if ($class == 'old') {
          if ($count++ == $limit) {
            break;
          }
        }
        $item['author'] = $item['committer']['name'];
        $item['date'] = $item['committed_date'];
        $item['url'] = 'https://github.com/' . Configure::read('Ballista.githubAccount') . '/' . $project . '/commit/' . $item['id'];

        $commits[$index] = $item;
      }

    } else {// Custom log list formatting from local hosting

      // Here, we need to run a loop and generate an array of the commit info
      // because the log output comes out in one line and it needs to be parsed
      foreach ($log as $line) {
        $items = explode('|-|', $line);
        $list = array();
        // Parse log
        foreach ($items as $item) {
          $arr = explode('||', $item);
          $list[$arr[0]] = $arr[1];
        }
        $list['class'] = $class;
        // Check if this is the last deployed commit
        if ($lastcommit == $list['id']) {
          $list['class'] = $class = 'old';
          // also set different style class for all commits hereafter
        }
        // Set URL to gitweb or any other user configured git web interface
        if (Configure::read('Ballista.gitWebUrl')) {
          $list['url'] = preg_replace(array('/{project}/', '/{commit}/'), array($project, $list['id']), Configure::read('Ballista.gitWebUrl'));
        } else {
          $list['url'] = '';
        }
        // If old commits are being displayed, then increment counter and stop at $limit
        if ($class == 'old') {
          if ($count++ == $limit) {
            break;
          }
        }

        array_push($commits, $list);
      }
    }

    $output = $this->_commit_looper($commits, $lastcommit, $project, $serverkey);
    return $output;
  }

  /**
   * Function to receive commit array and generate markup for them
   *
   * @param   array   $commits    Commits
   * @param   string  $lastcommit Last commit hash
   * @param   string  $project    Name of the project
   * @param   integer $serverkey  Index of the server array
   *
   * @return  string  $output     Neatly formatted commits
   */
  function _commit_looper($commits = null, $lastcommit = null, $project = null, $serverkey = null) {
    $output = '';
    // Loop through array and generate output
    foreach ($commits as $index => $item) {
      $output .= '<div class="' . $item['class'] . ' logitem" title="' . $item['message'] . '"">';
      $output .= '<div class="hash">' . $item['id'] . '</div>';
      $output .= '<div class="title">';
      $output .= '<input class="radio" type="radio" name="data[Log][' . $serverkey . '][commit]" value="' . $item['id'] . '" /> ';
      $output .= strlen($item['message']) > 36 ? substr_replace($item['message'], '..', 36) : $item['message'];
      if ($lastcommit == $item['id']) {
        $output .= '<span class="version">&#171; Current version on server</span>';
      }
      $output .= '</div>';
      $output .= '<div class="infoBox">';
      $output .= '<div class="author">' . $item['author'] . '</div>';
      $output .= '<div class="date">' . $item['date'] . '</div>';
      $output .= '<div class="commit">' . substr($item['id'], 0, 7) . '</div>';
      // Add changelist link to Web interface URL
      if ($item['url']) {
        $output .= '<div class="changelist">' . 
                    $this->Html->link(
                      $this->Html->image(
                        'view.png', array(
                          'alt' => 'View changelist', 
                          'title' => 'View changelist'
                        )
                      ), 
                      $item['url'], 
                      array('escape' => false, 'target' => '_blank')
                    ) . '</div>';
      }
      $output .= '</div>';
      $output .= '</div>';
    }

    return $output;
  }

}
?>
