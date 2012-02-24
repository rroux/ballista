<?php
/**
 * Projects Controller
 *
 * This controller contains the logic for projects CRUD functionality.
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

class ProjectsController extends AppController {

	var $name = 'Projects';
  
  /** 
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    // Access denied to certain methods if not admin
    if (($this->Session->read('User.admin') != 1) && ($this->action == 'add' || $this->action == 'edit' || $this->action == 'delete' || $this->action == 'status')) {
      $this->cakeError('accessDenied');
    }

    // Make sure ID is sent for all these actions. If not redirect to index page.
    if($this->action == 'view' || $this->action == 'status' || $this->action == 'delete'){
      if(!$this->params['pass'][0]){
  		  $this->_flash('Project not found!');
      }
    }

    // Access denied to certain methods if user does not have permission
    if ($this->action == 'view') {
      if (!array_key_exists($this->params['pass'][0], $this->Session->read('User.permissions'))) {
        $this->cakeError('accessDenied');
      }
    }
  }
    
  /**
   * Fetch and list all projects that the user has access to.
   * 
   * @return  object  $projects   Projects object
   */
  function index() {
    $allowed_projects = array_keys($this->Session->read('User.permissions'));
		$this->Project->recursive = 0;
		$this->paginate = array('conditions' => array('Project.id' => $allowed_projects), 
		                        'order' => array('Project.name' => 'asc'), 
		                        'limit' => 100); 
		$this->set('projects', $this->paginate());
	}

  /**
   * View a selected project
   * 
   * @param   integer $id       ID of the project to view
   * 
   * @return  object  $project  Project information
   */
	function view($id = null) {
		$this->set('project', $this->Project->read(null, $id));
	}

  /**
   * Add a new project to the system
   * 
   * @return  object  $servers  Servers object
   * @return  array   $hosts    Hosts array
   */
	function add() {
    // Get list of all servers
    $servers = $this->Project->Server->find('list');
    $hosts = $this->Project->hosts;

    if (!empty($this->data)) {
      // Stash away the server info to save only project info first. 
      $serverArray = $this->data['Server'];
      unset($this->data['Server']);
		  
      $this->Project->create();
			if ($this->Project->save($this->data)) {
        $id = $this->Project->getLastInsertId();

        // Now create instances from stashed serverArray
        foreach ($serverArray as $serverid => $serverinfo) {
            $instance = array();
            $instance['Instance']['project_id'] = $id;
            $instance['Instance']['server_id'] = $serverid;
            $instance['Instance']['path'] = $serverinfo['path'];
            $instance['Instance']['active'] = $serverinfo['active'];
            $this->Project->Instance->create();
            $this->Project->Instance->save($instance);
        }
        
        $this->_flash('The project has been saved');
			} else {
				$this->_flash('The project could not be saved. Please, try again.');
			}
		}

		$this->set(compact('servers', 'hosts'));
		$this->render('edit');
	}

  /**
   * Edit a project in the system
   * 
   * @param integer $id   ID of the project
   * 
   * @return  object  $servers    Servers object
   * @return  object  $instances  Instances object
   * @return  array   $hosts      Hosts array
   */ 
	function edit($id = null) {
		if (!$id && empty($this->data)) {
        $this->_flash('Invalid project!');
		}
    // Get list of all servers
		$servers = $this->Project->Server->find('list');
    $hosts = $this->Project->hosts;

		if (empty($this->data)) {
			$this->data = $this->Project->read(null, $id);
			$instances = array();
			foreach ($this->data['Server'] as $key => $serverdata) {
			  $instances[$serverdata['id']]['active'] = $serverdata['Instance']['active'];
			  $instances[$serverdata['id']]['path'] = $serverdata['Instance']['path'];
			}
		}else{
      // Get servers chosen from submitted form 
      $serverArray = $this->data['Server'];
      unset($this->data['Server']);
		  
      // Save project
			if ($this->Project->save($this->data)) {
        // Update instances
        foreach ($serverArray as $serverid => $serverinfo) {
          $instance = $this->Project->Instance->find('first', array('conditions' => array('Instance.project_id' => $id, 'Instance.server_id' => $serverid)));
          if(empty($instance)){
            $instance = array();
            $instance['Instance']['project_id'] = $id;
            $instance['Instance']['server_id'] = $serverid;
            $this->Project->Instance->create();
          }
          $instance['Instance']['path'] = $serverinfo['path'];
          $instance['Instance']['active'] = $serverinfo['active'];
          $this->Project->Instance->save($instance);
        }
			  
			  $this->_flash('The project has been saved');
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.', true));
			}
		}
		$this->set(compact('servers', 'instances', 'hosts'));
	}

  /** 
   * Toggle the status of a project between active and inactive
   * 
   * @param integer $id     ID of the project
   * @param boolean $active Active or Inactive
   */
  function status($id = null, $active){
    $newstatus = $active == 1 ? 0 : 1;
    $this->Project->read(null, $id);
    $this->Project->set('active', $newstatus);
    $this->Project->save();
    $this->redirect(array('action' => 'index'));
  }

  /**
   * Delete a project from the system
   * 
   * @param integer $id   ID of the project
   */
	function delete($id = null) {
		if ($this->Project->delete($id)) {
      $this->_flash('Project has been deleted');
		}
    $this->_flash('Project was not deleted');
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
