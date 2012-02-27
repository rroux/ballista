<?php
/**
 * Groups Controller
 *
 * This controller contains the logic for groups CRUD functionality
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
 * @copyright     Copyright 2011-2012, Aller Internett <it@allerinternett.no>
 * @author        Baheerathan Vykundanathan <thamba@allerinternett.no>
 * @package       Ballista
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.txt)
 */

class GroupsController extends AppController {

  var $name = 'Groups';

  /**
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    // Make sure only admin has access here
    if ($this->Session->read('User.admin') != 1) {
      $this->cakeError('accessDenied');
    }
  }

  /**
   * Fetch and list all groups available
   *
   * @return  object  $groups All groups defined in the database
   */
  function index() {
    $this->Group->recursive = 0;
    $this->set('groups', $this->paginate());
  }

  /**
   * View a selected group
   *
   * @param   integer $id     ID of the group to view
   *
   * @return  object  $group  Group information
   */
  function view($id = null) {
    if (!$id) {
      $this->Session->setFlash(__('Invalid group', true));
      $this->redirect(array('action' => 'index'));
    }
    $this->set('group', $this->Group->read(null, $id));
  }

  /**
   * Add a new group to the system
   *
   * @return  object  $instances  Instances object
   * @return  object  $users      Users object
   */
  function add() {
    if (!empty($this->data)) {
      $this->Group->create();
      if ($this->Group->save($this->data)) {
        $this->Session->setFlash(__('The group has been saved', true));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
      }
    }
    $instances = $this->Group->Instance->find('list');
    $users = $this->Group->User->find('list');
    $this->set(compact('instances', 'users'));
    $this->render('edit');
  }

  /**
   * Edit a group in the system
   *
   * @param integer $id   ID of the group
   *
   * @return  object  $instances  Instances object
   * @return  object  $users      Users object
   */
  function edit($id = null) {
    if (!$id && empty($this->data)) {
      $this->Session->setFlash(__('Invalid group', true));
      $this->redirect(array('action' => 'index'));
    }
    if (!empty($this->data)) {
      if ($this->Group->save($this->data)) {
        $this->Session->setFlash(__('The group has been saved', true));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Group->read(null, $id);
    }
    $instances = $this->Group->Instance->find('list');
    $users = $this->Group->User->find('list');
    $this->set(compact('instances', 'users'));
  }

  /**
   * Delete a group from the system
   *
   * @param integer $id   ID of the group
   */
  function delete($id = null) {
    if (!$id) {
      $this->Session->setFlash(__('Invalid id for group', true));
      $this->redirect(array('action' => 'index'));
    }
    if ($this->Group->delete($id)) {
      $this->Session->setFlash(__('Group deleted', true));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Group was not deleted', true));
    $this->redirect(array('action' => 'index'));
  }

}
?>
