<?php
/**
 * Instances Controller
 *
 * This controller contains the logic for instances CRUD functionality.
 * An instance is - the presence of a project on a server.
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

class InstancesController extends AppController {

  var $name = 'Instances';
  var $components = array('Git');
  var $helpers = array('Git');

  /**
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    // Access denied to certain methods if not admin
    if (($this->Session->read('User.admin') != 1) && ($this->action == 'index' || $this->action == 'edit' || $this->action == 'delete')) {
      $this->cakeError('accessDenied');
    }

    // Make sure ID is sent for all these actions. If not redirect to index page.
    if ($this->action == 'deploy' || $this->action == 'edit' || $this->action == 'delete') {
      if (!$this->params['pass'][0]) {
        $this->_flash('Instance not found!');
      }
    }
  }

  /**
   * Fetch and display a matrix of all projects and servers
   * This is used in the "manage permissions" page.
   *
   * @return  object  $projects   Projects object
   * @return  object  $servers    Servers object
   * @return  object  $groups     Groups object
   * @return  object  $instances  Instances object
   */
  function index() {
    $projects = $this->Instance->Project->find(
      'all', array(
        'order' => array('Project.name' => 'asc'), 
        'recursive' => 0
      )
    );
    $servers = $this->Instance->Server->find(
      'all', array(
        'recursive' => 0
      )
    );
    $groups = $this->Instance->Group->find(
      'all', array(
        'recursive' => 0
      )
    );

    $this->Instance->UnbindModel(array('hasMany' => array('Log')));
    $instances = $this->Instance->find('all');

    $this->set(compact('projects', 'servers', 'groups', 'instances'));
  }

  /**
   * Edit an instance in the system
   *
   * @param integer $id   ID of the instance
   *
   * @return  object  $projects   Projects object
   * @return  object  $servers    Servers object
   * @return  object  $groups     Groups object
   */
  function edit($id = null) {
    if (!$id && empty($this->data)) {
      $this->_flash('Invalid instance', true);
    }
    if (!empty($this->data)) {
      if ($this->Instance->save($this->data)) {
        $this->_flash('The instance has been saved', true);
      } else {
        $this->_flash('The instance could not be saved. Please, try again.', true);
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Instance->read(null, $id);
    }
    $projects = $this->Instance->Project->find('list');
    $servers = $this->Instance->Server->find('list');
    $groups = $this->Instance->Group->find('list');
    $this->set(compact('projects', 'servers', 'groups'));
  }

  /**
   * Delete an instance from the system
   *
   * @param integer $id   ID of the instance
   */
  function delete($id = null) {
    if ($this->Instance->delete($id)) {
      $this->_flash('Instance deleted');
    }
    $this->_flash('Instance was not deleted');
  }

  /**
   * Fetch recent deploy info for a project and display choice of
   * servers and branches to deploy to.
   *
   * @param   integer $id         ID of the instance
   * @param   string  $branch     Name of the branch
   * @param   integer $limit      Number of commits to show
   *
   * @return  object  $project    Project object
   * @return  object  $instances  Instances object
   * @return  integer $limit      Number of commits to show
   */
  function deploy($id = null, $branch = null, $limit = 3) {
    // Get ACL
    $acl = $this->Session->read('User.permissions');
    if (empty($acl[$id])) {
      $this->_flash('You do not have permissions to deploy this project');
    }

    // Get project info and check if project is active
    $project = $this->Instance->Project->find('first', array(
      'conditions' => array('id' => $id), 
      'recursive' => 0
    ));
    if ($project['Project']['active'] != 1) {
      $this->_flash('This project is inactive');
    }

    // Set the project notification users and message if no default is set
    $this->data['Instance']['notify'] = $project['Project']['notify'] ? $project['Project']['notify'] : Configure::read('Ballista.notifyUsers');
    $this->data['Instance']['message'] = $project['Project']['message'] ? $project['Project']['message'] : Configure::read('Ballista.notifyMessage');
    $this->data['Instance']['message'] = preg_replace(array('/{project}/'), array($project['Project']['name']), $this->data['Instance']['message']);

    // Find all instances of this project that the current user can deploy to
    $instances = $this->Instance->find('all', array(
      'conditions' => array(
        'Instance.project_id' => $id, 
        'Instance.server_id' => $acl[$id], 
        'Instance.active' => 1
      ), 
      'recursive' => 0
    ));

    // Find all branches available
    $branches = $this->Git->getBranches($project['Project']['name'], $instances[0]['Project']['path'], $project['Project']['host']);

    // Get commit log for each instance
    foreach ($instances as $key => $instance) {
      // Find the last commit on the server
      $instances[$key]['lastcommit'] = $this->Instance->Log->find(
        'first', array(
          'fields' => array('Log.commit'), 
          'conditions' => array(
            'instance_id' => $instance['Instance']['id'], 
            'branch'      => $branch, 
            'logtime < '  => date('Y-m-d H:i:s'), 
            'status'      => 'Completed'
          ), 
          'order' => array('Log.id DESC'), 
          'recursive' => -1
        )
      );

      if ($instance['Server']['branches'] == 1) {// Does server support deploy of branches?
        $instances[$key]['branches'] = $branches;
      } else {
        $instances[$key]['branches'] = array(Configure::read('Ballista.master'));
      }

      $instances[$key]['commits'] = $this->Git->getLog($project['Project']['name'], $instance['Project']['path'], $project['Project']['host'], $branch);

      // Get history of deploys for this instance
      $instances[$key]['history'] = $this->Instance->Log->find(
        'all', array(
          'fields' => array('status', 'comment', 'commit', 'updated', 'User.username'), 
          'conditions' => array(
            'Log.instance_id' => $instance['Instance']['id'], 
            'branch' => $branch
          ), 
          'order' => array('Log.id DESC'), 
          'limit' => 5
        )
      );
    }

    $this->set(compact('project', 'instances', 'limit'));
  }

  /**
   * Ajax call to fetch all commits of a branch
   *
   * @param   integer $id         ID of the instance
   * @param   string  $branch     Name of the branch
   * @param   integer $serverkey  Index of the server array
   * @param   integer $limit      Number of commits to show
   *
   * @return  array   $commitlog  Log of commits
   * @return  object  $instance   The instance object
   * @return  string  $branch     Name of the branch
   * @return  object  $lastcommit The last commit object
   * @return  integer $serverkey  Index of the server array
   * @return  integer $limit      Number of commits to show
   */
  function branch_commits($id = null, $branch = null, $serverkey = 0, $limit = 3) {
    $instance = $this->Instance->find(
      'first', array(
        'conditions' => array('Instance.id' => $id), 
        'recursive' => 0
      )
    );

    // Find the last commit on the server in this branch
    $lastcommit = $this->Instance->Log->find(
      'first', array(
        'fields' => array('Log.commit'), 
        'conditions' => array(
          'instance_id' => $id, 
          'branch' => $branch, 
          'logtime < ' => date('Y-m-d H:i:s'), 
          'status' => 'Completed'
        ), 
        'order' => array('Log.id DESC'), 
        'recursive' => -1
      )
    );

    // Get the history of deploys for this instance
    $instance['history'] = $this->Instance->Log->find(
      'all', array(
        'fields' => array('status', 'comment', 'commit', 'updated', 'User.username'), 
        'conditions' => array(
          'Log.instance_id' => $instance['Instance']['id'], 
          'branch' => $branch
        ), 
        'order' => array('Log.id DESC'), 
        'limit' => 5
      )
    );

    $commitlog = $this->Git->getLog($instance['Project']['name'], $instance['Project']['path'], $instance['Project']['host'], $branch);

    $this->layout = 'ajax';
    $this->set(compact('commitlog', 'instance', 'branch', 'lastcommit', 'serverkey', 'limit'));
  }

  /**
   * Display a flash message to user and redirect to index page
   *
   * @param string  $message  The message to display to the user
   * @param boolean $inner    Redirect to method in same controller or another controller
   */
  function _flash($message, $inner) {
    $this->Session->setFlash(__($message, true));
    if ($inner) {
      $this->redirect(array('action' => 'index'));
    } else {
      $this->redirect(array('controller' => 'projects', 'action' => 'index'));
    }
  }

}
?>
