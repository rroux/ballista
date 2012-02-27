<?php
/**
 * Servers Controller
 *
 * This controller contains the logic for servers CRUD functionality.
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

class ServersController extends AppController {

  var $name = 'Servers';

  /**
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    if ($this->Session->read('User.admin') != 1) {
      $this->cakeError('accessDenied');
    }
  }

  /**
   * Fetch and list all servers
   *
   * @return  object  $servers   Servers object
   */
  function index() {
    $this->Server->recursive = 0;
    $this->set('servers', $this->paginate());
  }

  /**
   * View a selected server
   *
   * @param   integer $id       ID of the server to view
   *
   * @return  object  $server  server information
   */
  function view($id = null) {
    if (!$id) {
      $this->Session->setFlash(__('Invalid server', true));
      $this->redirect(array('action' => 'index'));
    }
    $this->set('server', $this->Server->read(null, $id));
  }

  /**
   * Add a new server to the system
   *
   * @return  object  $projects  Project object
   */
  function add() {
    if (!empty($this->data)) {
      $this->Server->create();
      if ($this->Server->save($this->data)) {
        $this->Session->setFlash(__('The server has been saved', true));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The server could not be saved. Please, try again.', true));
      }
    }
    $projects = $this->Server->Project->find('list');
    $this->set(compact('projects'));
    $this->render('edit');
  }

  /**
   * Edit a server in the system
   *
   * @param   integer $id       ID of the server
   *
   * @return  object  $projects Projects object
   */
  function edit($id = null) {
    if (!$id && empty($this->data)) {
      $this->Session->setFlash(__('Invalid server', true));
      $this->redirect(array('action' => 'index'));
    }
    if (!empty($this->data)) {
      if ($this->Server->save($this->data)) {
        $this->Session->setFlash(__('The server has been saved', true));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The server could not be saved. Please, try again.', true));
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Server->read(null, $id);
    }
    $projects = $this->Server->Project->find('list');
    $this->set(compact('projects'));
  }

  /**
   * Delete a server from the system
   *
   * @param integer $id   ID of the server
   */
  function delete($id = null) {
    if (!$id) {
      $this->Session->setFlash(__('Invalid id for server', true));
      $this->redirect(array('action' => 'index'));
    }
    if ($this->Server->delete($id)) {
      $this->Session->setFlash(__('Server deleted', true));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Server was not deleted', true));
    $this->redirect(array('action' => 'index'));
  }

}
?>
