<?php
/**
 * Logs Controller
 *
 * This controller contains the logic for logs CRUD functionality.
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

App::import('Sanitize');
class LogsController extends AppController {

	var $name = 'Logs';
  var $uses = array('Log', 'Notification');
  var $components = array('RequestHandler', 'Git');
  
  /** 
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    // Make sure ID is sent for all actions. If not redirect to index page.
    if($this->action == 'status' || $this->action == 'delete'){
      if(!$this->params['pass'][0]){
  		  $this->_flash('Log not found!');
      }
    }

    // Access denied to certain methods if user does not have permission
    if ($this->action == 'index' && $this->params['pass'][0]) {
      if (!array_key_exists($this->params['pass'][0], $this->Session->read('User.permissions'))) {
        $this->cakeError('accessDenied');
      }
    }
  }

  /**
   * Fetch and list logs information of all projects.
   * If ID of a project is sent, then only logs 
   * from that project will be fetched.
   * 
   * @param   integer $id       ID of the project
   * @param   boolean $dispatch Whether or not to dispatch a deploy
   * 
   * @return  object  $actionlogs Logs information object
   * @return  object  $servers    Servers object
   * @return  object  $projects   Projects object
   * @return  boolean $dispatch   Whether or not to dispatch
   */
	function index($id = null, $dispatch = null) {
    $this->Log->recursive = 0;
    if(empty($id)){
      $allowed_projects = array_keys($this->Session->read('User.permissions'));
      $this->paginate = array(
        'conditions' => array('Instance.project_id' => $allowed_projects),
        'fields' => array(
          'id','user_id','instance_id','status','commit','branch','comment','updated','logtime','output',
          'Instance.id','Instance.project_id','Instance.server_id','Instance.active',
          'User.id','User.username'
        ), 
        'order' => array('logtime DESC', 'Log.id DESC'));
      $projects = $this->Log->Instance->Project->find('list');
    }else{
      $this->paginate = array('conditions' => array('Instance.project_id' => $id), 'order' => array('logtime DESC', 'Log.id DESC'));
      $projects = $this->Log->Instance->Project->find('list', array('conditions' => array('Project.id' => $id)));
    }

    $actionlogs = $this->paginate();
    $servers = $this->Log->Instance->Server->find('list');

    $this->set(compact('actionlogs', 'servers', 'projects', 'dispatch'));
	}

  /**
   * Create a log record and execute a deploy
   * 
   * @param integer $id   ID of the project
   */
  function execute($id = null) {
    if(!empty($this->data)){
      // Get groups for current user
      $groups = $this->Session->read('User.groups');

      // Check if project is active
      $project = $this->Log->query(Sanitize::escape('SELECT name, active, host FROM projects WHERE id = ' . $id, 'default'));
      if ($project[0]['projects']['active'] != 1) {
        $this->_flash('Error! This project is not active');
      }
      
      foreach ($this->data['Log'] as $key => $val) {
        // If no server instance then unset $key in Log array to avoid execute for that server instance
        if ($val['instance_id'] == 0) {
          unset($this->data['Log'][$key]);
          continue;
        }

        // Set status
        $this->data['Log'][$key]['status'] = ($this->data['Log'][$key]['logtime'] > date('Y-m-d H:i')) ? 'Upcoming' :  'Running';
        
        // If comment was specified, then copy comment to all instances, if not fetch and use last commit message as comment.
    	  if ($this->data['Comment'] == 'deploy comment' || $this->data['Comment'] == '') {
          $this->data['Log'][$key]['comment'] = $this->Git->getLastMessage($project[0]['projects']['name'], $this->data['Path'], $project[0]['projects']['host'], $this->data['Log'][$key]['branch']); 
    	  } else {
    	    $this->data['Log'][$key]['comment'] = $this->data['Comment'];
    	  }
    	  
      	// Check if users group can deploy
      	$check = $this->Log->query("SELECT * FROM instances_groups WHERE instance_id = '".$val['instance_id']."' AND group_id IN (".implode(',', array_keys($groups)).")");
        if (empty($check)) {
          $this->_flash('Error! Permission denied to deploy');
        }
      }
      unset($this->data['Comment']);
      
      // Save data  
      $this->Log->saveAll($this->data['Log']);

      // If mail notification is requested then save the mail and message values
      if ($this->data['Instance']['sendmail']) {
        $this->data['Notification']['log_id'] = $this->Log->id;
        $this->Notification->save($this->data['Notification']);
      }
      
      $this->redirect(array('action' => 'index', $id, 1));
    }
  }

  /**
   * Ajax request to call the dispatcher to execute a deploy 
   */
  function shellexec() {
    shell_exec(ROOT . '/cake/console/cake -app ' . APP . ' dispatch');
    // View rendering not required
    $this->autoRender = false;
  }

  /**
   * Returns the status of a particular log record
   * 
   * @param   integer $id     ID of the log
   * 
   * @return  string  $status Status of the retrieved log
   */
  function status($id = null) {
    $this->layout = 'ajax';
    $this->set('status', $this->Log->field('status'));
  }

  /**
   * Delete a log from the system
   * 
   * @param integer $id   ID of the log
   */
	function delete($id = null) {
    // Do not allow delete if log is in the past
		$today = date('Y-m-d H:i:s');
	  if($this->Log->field('logtime') < $today){
  		$this->_flash('Log is in the past and it cannot be deleted');
    }

    // Do not allow delete if log doesnt belong to user and user is not admin
    if($this->Session->read('User.admin') != 1 && $this->Session->read('User.id') != $this->Log->field('user_id')){
  		$this->_flash('You do not have access to delete logs that were not created by you.');
    }

		if ($this->Log->delete($id)) {
			$this->_flash('Log deleted');
		}
		$this->_flash('Log was not deleted');
	}

  /** 
   * Display a flash message to user and redirect to index page
   * 
   * @param string  $message  The message to display to the user
   */
	function _flash($message) {
		$this->Session->setFlash(__($message, true));
		$this->redirect(array('action' => 'index'));
	}

}
?>
