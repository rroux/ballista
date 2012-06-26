<?php
/**
 * Tags Controller
 *
 * This controller contains the logic for tags CRUD functionality.
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
 * @author        JustAdam <adam.bell@allerinternett.no>
 * @package       Ballista
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.txt)
 */

class TagsController extends AppController {
  var $name = 'Tags';
  
  /**
   * Perform certain tasks before any methods in this controller can be run
   */
  function beforeFilter() {
    if ($this->Session->read('User.admin') != 1) {
      $this->cakeError('accessDenied');
    }
  }

  /**
   * Display a list of all tags
   *
   * @return  object  $tags   Tags object
   */
  function index() {
    $this->Tag->recursive = 0;
    $this->set('tags', $this->paginate());
  }

  /**
   * Add a new tag to the system
   */
  function add() {
    if (!empty($this->data)) {
      $this->Tag->create();
      if ($this->Tag->save($this->data)) {
        $this->_flash('The tag has been saved');
      } else {
        $this->_flash('The tag could not be saved. Please, try again.');
      }
    }

    $this->render('edit');
  }

  /**
   * Edit a tag in the system
   *
   * @param integer $id   ID of the project
   */
  function edit($id = null) {
    if (!$id && empty($this->data)) {
      $this->Session->setFlash(__('Invalid tag', true));
      $this->redirect(array('action' => 'index'));
    }
    if (!empty($this->data)) {
      if ($this->Tag->save($this->data)) {
        $this->Session->setFlash(__('The tag has been saved', true));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The tag could not be saved. Please try again.', true));
      }
    }
    
    if (empty($this->data)) {
      $this->data = $this->Tag->read(null, $id);
    }
  }
  
  /**
   * Delete a tag from the system
   *
   * @param integer $id   ID of the project
   */
  function delete($id = null) {
    if (!$id) {
      $this->Session->setFlash(__('Invalid id for tag', true));
      $this->redirect(array('action' => 'index'));
    }
    if ($this->Tag->delete($id)) {
      $this->Session->setFlash(__('Tag deleted', true));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Tag was not deleted', true));
    $this->redirect(array('action' => 'index'));
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