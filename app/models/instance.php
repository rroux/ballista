<?php
/**
 * Instance Model
 *
 * Model class for instance table
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

class Instance extends AppModel {
  var $name = 'Instance';
  var $validate = array(
    'project_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'server_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'path' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'active' => array(
      'boolean' => array(
        'rule' => array('boolean'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
  );
  //The Associations below have been created with all possible keys, those that are not needed can be removed

  var $belongsTo = array(
    'Project' => array(
      'className' => 'Project',
      'foreignKey' => 'project_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Server' => array(
      'className' => 'Server',
      'foreignKey' => 'server_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );

  var $hasMany = array(
    'Log' => array(
      'className' => 'Log',
      'foreignKey' => 'instance_id',
      'dependent' => false,
      'conditions' => '',
      'fields' => '',
      'order' => 'logtime DESC',
      'limit' => '1',
      'offset' => '',
      'exclusive' => '',
      'finderQuery' => '',
      'counterQuery' => ''
    )
  );


  var $hasAndBelongsToMany = array(
    'Group' => array(
      'className' => 'Group',
      'joinTable' => 'instances_groups',
      'foreignKey' => 'instance_id',
      'associationForeignKey' => 'group_id',
      'unique' => true,
      'conditions' => '',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'finderQuery' => '',
      'deleteQuery' => '',
      'insertQuery' => ''
    )
  );

}
?>
