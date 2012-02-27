<?php
/**
 * Instance_Group Model
 *
 * Model class for instance_group table
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

class InstancesGroup extends AppModel {
  var $name = 'InstancesGroup';
  //The Associations below have been created with all possible keys, those that are not needed can be removed

  var $belongsTo = array(
    'Instance' => array(
      'className' => 'Instance',
      'foreignKey' => 'instance_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Group' => array(
      'className' => 'Group',
      'foreignKey' => 'group_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );
}
?>
