<?php
/**
 * Git Component
 *
 * This is a component that performs all the Git related functions
 * which controllers use to get information from the Git repository
 *
 * Ballista : Code Deployment System
 * Copyright 2011-2012, Baheerathan Vykundanathan <thamba@allerinternett.no>
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
 * @copyright     Copyright 2011-2012, Baheerathan Vykundanathan <thamba@allerinternett.no>
 * @package       Ballista
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.txt)
 */

class GitComponent extends Object {

  /**
   * Run a git log of the project and return the last 35 commits
   * Githib also returns the last 35 commits. 
   * 
   * @param string  $project  Name of the project in git
   * @param string  $path     Path to the project in the repository
   * @param string  $host     Type of hosting (Git or Local)
   * @param string  $branch   Name of the branch
   * 
   * @return array  $output   Log of last commits from a project
   */
  function getLog($project, $path, $host, $branch) {
    if ($host == 'Github') {
      $github = new Github_Client();
      $commits = $github->getCommitApi()->getBranchCommits(Configure::read('Ballista.githubAccount'), $project, $branch);
      $arraypass = 0;
      foreach ($commits as $key => $item) {
        $output[$key] = $item;
      }
    } else {
      $format = 'git log --pretty=format:"id||%H|-|message||%s|-|author||%cn|-|date||%cr" -35';
      if (chdir($path)) {
        exec($format.' '.$branch, $output);
      } else {
        $this->cakeError('missingPath');
      }
    }
    return $output;
  }

  /**
   * Get all branches of a project
   * 
   * @param string  $project  Name of the project
   * @param string  $path     Path to the project in the repository
   * @param string  $host     Host of the project (Git or Local)
   * 
   * @return array  $output   List of branches available in the project 
   */
  function getBranches($project, $path, $host) {
    if ($host == 'Github') { // Project hosted on Github
        $github = new Github_Client();
        $branches = $github->getRepoApi()->getRepoBranches(Configure::read('Ballista.githubAccount'), $project);
        $output = array_keys($branches);
    } else { // Project hosted on local server
      if (chdir($path)) {
        exec('git branch', $output);
      } else {
        $this->cakeError('missingPath');
      }

      // Remove * symbol in branch names
      foreach ($output as $key => $item) {
        $output[$key] = preg_replace('/^\* /', '', $item);
      }
    }

    // Move master to the top of the branches array
    if ($key = array_search(Configure::read('Ballista.master'), $output)) {
      unset($output[$key]);
      array_unshift($output, Configure::read('Ballista.master'));
    }
    
    return $output;
  }

  /**
   * Get last commit message of branch
   * 
   * @param string  $project  Name of the project in git
   * @param string  $path     Path to the project in the repository
   * @param string  $host     Type of hosting (Git or Local)
   * @param string  $branch   Name of the branch
   * 
   * @return string $output   Last commit message
   */
  function getLastMessage($project, $path, $host, $branch) {
    if ($host == 'Github') { // Project hosted on Github
      $github = new Github_Client();
      $commits = $github->getCommitApi()->getBranchCommits(Configure::read('Ballista.githubAccount'), $project, $branch);
      $output = $commits[0]['message'];
    } else { // Project hosted on local server
      if (chdir($path)) {
        exec('git log --format=%s -1 ' . $branch, $message);
      } else {
        $this->cakeError('missingPath');
      }
      $output = $message[0];
    }
    return $output;
  }

  /**
   * Get last commit hash ID of branch
   * 
   * @param string  $project  Name of the project in git
   * @param string  $path     Path to the project in the repository
   * @param string  $host     Type of hosting (Git or Local)
   * @param string  $branch   Name of the branch
   * 
   * @return string $output   Last commit hash ID
   */
  function getLastCommitId($project, $path, $host, $branch) {
    if ($host == 'Github') { // Project hosted on Github
      $github = new Github_Client();
      $commits = $github->getCommitApi()->getBranchCommits(Configure::read('Ballista.githubAccount'), $project, $branch);
      $output = $commits[0]['id'];
    } else { // Project hosted on local server
      if (chdir($path)) {
        exec('git rev-parse ' . $branch, $commit);
      } else {
        $this->cakeError('missingPath');
      }
      $output = $commit[0];
    }
    return $output;
  }

}
?>