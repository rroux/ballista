<?php
/**
 * Coderunner task
 *
 * This class contains the execute method that actually
 * performs the git commands on the servers.
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

class CoderunnerTask extends Shell {

  /**
   * Execute the deploy command
   *
   * @param   object  $ssh          The SSH connection handle
   * @param   string  $project      Name of the project
   * @param   string  $host         Host type of project (Git/Local)
   * @param   string  $destination  Location where project resides on the server
   * @param   string  $path         Path to the project on the repository
   * @param   string  $commit       Commit hash
   * @param   string  $branch       Name of the branch
   *
   * @return  string  $output       Output from git command
   */
  function execute($ssh = null, $project = null, $host = null, $destination = null, $path = null, $commit = 'HEAD', $branch = null) {
    if (!empty($destination)) {
      $output = "Performing deploy... \n";

      // Check if path exists on server.
      $dircheck = $ssh->exec("[ -d '" . $destination . "' ] && echo 'found'");
      if (strpos($dircheck, 'found') === false) {

        // Create project on server if not found
        $command = "mkdir -p " . $destination . "; cd " . $destination . "; ";
        if ($host == 'Github') {
          $command .= "git clone git://github.com/" . Configure::read('Ballista.githubAccount') . "/" . $project . " ./";
        } elseif (Configure::read('Ballista.gitoliteProtocol')) {
          $command .= "git clone " . Configure::read('Ballista.gitoliteProtocol') . ":" . $project . " ./";
        } else {
          $command .= "git clone " . Configure::read('Ballista.accessProtocol') . $path . " ./";
        }
        // Perform the command
        $output .= $ssh->exec($command);

        if ($branch != Configure::read('Ballista.master')) {
          // If branch is not master, then go into the project and checkout the branch from the repo
          $output .= $ssh->exec("cd " . $destination . "; git checkout -b " . $branch . " origin/" . $branch);
        }

      } else {
        // Path found, so project already exists. Just git pull.
        $output .= $ssh->exec("cd " . $destination . "; git fetch; git reset --hard origin/" . $branch);
      }

      return $output;

    } else {
      return "Error: Undefined path or action. Cannot execute.\n";
    }
  }

}
?>