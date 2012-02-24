<?php
/**
 * Hooks tasks
 *
 * This class contains the methods to run hooks
 * after a deploy is performed on the server. 
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

class HooksTask extends Shell {
  
  /**
   * Hooks that can be executed after a deploy is performed.
   * Hooks can be setup in the config folder. 
   * Read the README file for syntax
   * 
   * @param   object  $ssh          The SSH connection handle
   * @param   string  $server       IP/hostname of the server
   * @param   string  $project      Name of the project
   * @param   string  $host         Host type of project (Git/Local)
   * @param   string  $destination  Location where project resides on the server
   * @param   string  $path         Path to the project on the repository
   * @param   string  $commit       Commit hash
   * @param   string  $branch       Name of the branch
   * @param   string  $user         Name of the user who performed the deploy
   * 
   * @return  string  $output       Output from the hook execution 
   */
  function execute($ssh = null, $server = null, $project = null, $host = null, $destination = null, $path = null, $commit = 'HEAD', $branch = null, $user = null) {
    if (!empty($destination)) {

      // Placeholders and their respective parameters that can be substituted in the hook commands
      $holders = array('{$server}', '{$project}', '{host}', '{$destination}', '{$commit}', '{$branch}', '{$user}');
      $params = array($server, $project, $host, $destination, $commit, $branch, $user);
      
      // Start output
      $output = "\n\nRunning deploy hooks...\n";

      // Parse the deploy hook file
      $hooks = parse_ini_file(ROOT . DS . APP_DIR . DS . 'config/hooks/Deploy.ini', true);
      
      // Run global hook 
      $output .= $this->_runhook($ssh, $holders, $params, $hooks['global']['default']);
      
      // Run server hook
      $output .= $this->_runhook($ssh, $holders, $params, $hooks[$server]['default']);

      // Run project hook
      $output .= $this->_runhook($ssh, $holders, $params, $hooks[$server][$project]['default']);

      // Run branch hook
      $output .= $this->_runhook($ssh, $holders, $params, $hooks[$server][$project][$branch]);
      
      // End output and return
      $output .= "\nHooks execution completed.";
      return $output;
      
    } else {
      return "Error: Undefined path. Cannot run hooks.\n";
    }
  }


  /**
   * Check if the hook command was found, substitute the variables and execute it
   * 
   * @param   object  $ssh          SSH connection handle
   * @param   array   $holders      Array of replacement terms
   * @param   array   $params       Array of variables to replace
   * @param   string  $command      Command to execute
   *  
   * @return  string  $ssh->exec()  Output from the command 
   */
  function _runhook($ssh = null, $holders = null, $params = null, $command = null) {
    if (!empty($command)) {
      // Substitute the placeholders with the parameters
      $command = str_replace($holders, $params, $command);
      
      // Execute the command and return
      return $ssh->exec($command);
    }
  }
  
}
?>