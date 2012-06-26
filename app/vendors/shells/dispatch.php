<?php
/**
 * Dispatch shell script
 *
 * This shell script dispatches a deploy request
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

set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors/phpseclib');
include_once ('Net/SSH2.php');
include_once ('Crypt/RSA.php');
App::import('Component', 'Email');

class DispatchShell extends Shell {
  var $uses = array('Log', 'Notification');
  var $tasks = array('Coderunner', 'Hooks');

  /**
   * Find all projects that waiting to be deployed and deploy them
   *
   * @return  string  $output   Output from git or an error message
   */
  function main() {
    // Find all projects to deploy - those with status 1 or 2
    $this->Log->User->UnbindModel(array('hasMany' => array('Log')));
    $this->Log->Instance->UnbindModel(array('hasMany' => array('Log'), 'hasAndBelongsToMany' => array('Group')));
    $projects = $this->Log->find(
      'all', array(
        'conditions' => array(
          'Log.status' => array('Running', 'Upcoming'), 
          'Log.logtime <=' => date('Y-m-d H:i:s')
        ), 
        'recursive' => 2
      )
    );

    if (isset($projects[0])) {
      // Print current time
      $this->out('Running at: ' . date('d.m.Y H:i'));

      // Loop through all projects to execute
      foreach ($projects as $key => $val) {
        // Print execution message
        $this->out('Executing deploy of ' . $val['Instance']['Project']['name'] . ' (' . $val['Log']['branch'] . ') to ' . $val['Instance']['Server']['hostname'] . ':' . $val['Instance']['path']);

        $destination = $val['Instance']['path'];
        $branch = $val['Log']['branch'];

        // If branch is not master, then set path to branch path
        if ($branch != Configure::read('Ballista.master')) {
          $destination = preg_replace('/(.*)\/(.*)/', "$1/$branch.$2", $destination);
        }

        // Obtain an SSH connection to the server
        $ssh = $this->_connector($val['Instance']['Server']['hostname']);

	// Run the pre hooks
        $output = $this->Hooks->execute(
          'pre',     
          $ssh, 
          $val['Instance']['Server']['server'], 
          $val['Instance']['Project']['name'], 
          $val['Instance']['Project']['host'], 
          $destination, 
          $val['Instance']['Project']['path'], 
          $val['Log']['commit'], 
          $branch, 
          $val['User']['username']
        );
	$output .= "\n\n";

        // Execute the coderunner
        $output .= $this->Coderunner->execute(
          $ssh, 
          $val['Instance']['Project']['name'], 
          $val['Instance']['Project']['host'], 
          $destination, 
          $val['Instance']['Project']['path'], 
          $val['Log']['commit'], 
          $branch
        );
	$output .= "\n\n";

        // Set the status and run hooks
        if (strpos($output, 'Error:') !== false || strpos($output, 'fatal:') !== false) {
          $status = 'Failed';
        } else {
          $status = 'Completed';
          $output .= $this->Hooks->execute(
	    'post', 	
            $ssh, 
            $val['Instance']['Server']['server'], 
            $val['Instance']['Project']['name'], 
            $val['Instance']['Project']['host'], 
            $destination, 
            $val['Instance']['Project']['path'], 
            $val['Log']['commit'], 
            $branch, 
            $val['User']['username']
          );

          // Send notification if necessary
          $notify = $this->Notification->findByLogId($val['Log']['id']);
          if (!empty($notify)) {
            $subject = preg_replace(array('/{project}/', '/{comment}/'), array($val['Instance']['Project']['name'], $val['Log']['comment']), Configure::read('Ballista.notifySubject'));
            $this->sendmail($notify['Notification']['notify'], $subject, $notify['Notification']['message']);
          }
        }

        // Update the log table
        $this->Log->id = $val['Log']['id'];
        $this->Log->saveField('status', $status);
        $this->Log->saveField('output', $output);

        $this->out($output);
      }
    } else {
      $this->out('Nothing to execute. Stopping script.');
    }
  }

  /**
   * Connector to establishes the ssh connection to the server
   *
   * @param   string  $server   Server IP/hostname
   *
   * @return  object  $ssh    SSH connection handle
   */
  function _connector($server = null) {
    $ssh = new Net_SSH2($server);

    // Check if key verification is required
    if (Configure::read('Ballista.privateKey') != '') {
      $key = new Crypt_RSA();
      $key->loadKey(file_get_contents(APP . 'config/' . Configure::read('Ballista.privateKey')));
    } else {
      $key = Configure::read('Ballista.remotePass');
    }

    // Login using the configured credentials
    if (!$ssh->login(Configure::read('Ballista.remoteUser'), $key)) {
      // Could not connect. Return error message
      return "Error: Unable to authenticate. Please check the connection to the server and the username and password in the config file\n";
    }

    return $ssh;
  }

  /**
   * Function to send out an email notification about a deploy
   *
   * @param boolean $notify   Whether or not to notify
   * @param string  $subject  Subject of the notification
   * @param string  $message  Notification message
   */
  function sendmail($notify = null, $subject = null, $message = null) {
    if (!empty($notify) && !empty($message)) {
      $this->Email = &new EmailComponent(null);
      $this->Email->from = Configure::read('Ballista.mail');
      $this->Email->to = $notify;
      $this->Email->subject = $subject;
      $this->Email->send($message);
      $this->out('Email notification sent');
    }
  }

}
?>
