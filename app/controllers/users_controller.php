<?php
/**
 * Users Controller
 *
 * This controller contains the logic for users CRUD functionality.
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

class UsersController extends AppController {

  var $name = 'Users';

  /**
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    $this->Auth->autoRedirect = false;
    $this->Auth->userScope = array('User.active' => '1');

    // Access denied to certain methods if not admin
    if (($this->Session->read('User.admin') != 1) && ($this->action == 'add' || $this->action == 'delete' || $this->action == 'status')) {
      $this->cakeError('accessDenied');
    }

    // Make sure ID is sent for all these actions. If not redirect to index page.
    if ($this->action == 'view' || $this->action == 'status' || $this->action == 'delete') {
      if (!$this->params['pass'][0]) {
        $this->_flash('User not found!');
      }
    }
  }

  /**
   * Fetch and list all users
   *
   * @return  object  $users   Users object
   */
  function index() {
    $this->User->recursive = 0;
    $this->set('users', $this->paginate());
  }

  /**
   * View a selected user
   *
   * @param   integer $id   ID of the user to view
   *
   * @return  object  $user User information
   */
  function view($id = null) {
    $this->set('user', $this->User->read(null, $id));
  }

  /**
   * Add a new user to the system
   *
   * @return  object  $groups  Groups object
   */
  function add() {
    if (!empty($this->data)) {
      $this->User->create();
      if ($this->User->save($this->data)) {
        $this->_flash('The user has been saved');
      } else {
        $this->_flash('The user could not be saved. Please, try again.');
      }
    }
    $groups = $this->User->Group->find('list');
    $this->set(compact('groups'));
    $this->render('edit');
  }

  /**
   * Edit a user in the system
   *
   * @param   integer $id     ID of the user
   *
   * @return  object  $groups Groups object
   */
  function edit($id = null) {
    if (!$id && empty($this->data)) {
      $this->_flash('Invalid user');
    }

    // Can't allow edit if user is not admin and profile doesn't belong to user
    if ($this->Session->read('User.id') != $id && $this->Session->read('User.admin') != 1) {
      $this->cakeError('accessDenied');
    }

    if (!empty($this->data)) {
      // Make sure only admin can change groups
      if ($this->Session->read('User.admin') != 1) {
        $this->data['Group'] = '';
      }
      // If password is hash of empty string, leave password unchanged
      if ($this->data['User']['password'] == '563d8aa8f443ba61a968ecf5b0e382b89d972bed') {
        unset($this->data['User']['password']);
      }

      if ($this->User->save($this->data)) {
        $this->_flash('The user has been saved');
      } else {
        $this->_flash('The user could not be saved. Please, try again.');
      }
    }
    if (empty($this->data)) {
      $this->data = $this->User->read(null, $id);
      $this->data['User']['password'] = '';
    }
    $groups = $this->User->Group->find('list');
    $this->set(compact('groups'));
  }

  /**
   * Toggle the status of a user between active and inactive
   *
   * @param integer $id     ID of the user
   * @param boolean $active Active or Inactive
   */
  function status($id = null, $active) {
    $newstatus = $active == 1 ? 0 : 1;
    $this->User->read(null, $id);
    $this->User->set('active', $newstatus);
    $this->User->save();
    $this->redirect(array('action' => 'index'));
  }

  /**
   * Delete a user from the system
   *
   * @param integer $id   ID of the user
   */
  function delete($id = null) {
    if ($this->User->delete($id)) {
      $this->_flash('User deleted');
    }
    $this->_flash('User was not deleted. This user may have references in the Log.');
  }

  /**
   * Log the user into the system and store his group
   * information in the session
   */
  function login() {
    if ($this->Auth->user()) {

      $user = $this->Auth->user();
      $this->Session->write('User', $user['User']);

      // Fetch groups
      $this->User->unbindModel(array('hasMany' => array('Log')));
      $userobj = $this->User->find(
        'first', array(
          'conditions' => array('User.id' => $user['User']['id'])
        )
      );

      // Get users groups
      $groups = array();
      foreach ($userobj['Group'] as $key => $val) {
        $groups[$val['id']] = $val['group'];
      }

      // Get users permissions
      $acl = $this->User->query("
        SELECT DISTINCT Instance.project_id, Instance.server_id 
        FROM instances_groups InstanceGroup, instances Instance 
        WHERE InstanceGroup.instance_id = Instance.id 
        AND InstanceGroup.group_id in (" . implode(',', array_keys($groups)) . ')', 
        $cachequeries = false
      );

      // Create a Site => Instance permissions list
      $permissions = array();
      foreach ($acl as $data => $ins) {
        $permissions[$ins['Instance']['project_id']][] = $ins['Instance']['server_id'];
      }

      // Write it into the session variable
      $this->Session->write('User.groups', $groups);
      $this->Session->write('User.permissions', $permissions);

      if (in_array('Admin', $groups)) {
        $this->Session->write('User.admin', '1');
      }

      // Redirect to Projects
      $this->redirect(array('controller' => 'projects', 'action' => 'index'));
    }
  }

  /**
   * Log the user out of the system
   */
  function logout() {
    $this->Session->delete('User');
    $this->redirect($this->Auth->logout());
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
