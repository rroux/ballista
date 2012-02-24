<div id="menu">
	<div class="actions">
	  <fieldset>
	    <legend>Projects</legend>
		  <ul>
			  <?php 
			    echo '<li>';
        echo $this->Html->link(
          $this->Html->image('project.png').' View Projects', 
          array('controller' => 'projects', 'action' => 'index'), 
          array('escape' => false)
        ); 
        echo '</li>';

        if ($this->Session->read('User.admin')) { 
		      echo '<li>';
          echo $this->Html->link(
            $this->Html->image('project_add.png').' Add Project', 
            array('controller' => 'projects', 'action' => 'add'), 
            array('escape' => false)
          );
          echo '</li>';
        }

        echo '<li>';
        echo $this->Html->link(
          $this->Html->image('table.png').' Activity Log', 
          array('controller' => 'logs', 'action' => 'index'), 
          array('escape' => false)
        );
        echo '</li>';
      ?>
	    </ul>
	  </fieldset>
	    
	  <fieldset>
	    <legend>Users</legend>
	    <ul>
	      <?php 
	        echo '<li>';
		    echo $this->Html->link(
          $this->Html->image('user.png').' View Users', 
          array('controller' => 'users', 'action' => 'index'),
          array('escape' => false)
        );
        echo '</li>';

        if ($this->Session->read('User.admin')) { 
		      echo '<li>';
          echo $this->Html->link(
            $this->Html->image('user_add.png').' Add User', 
            array('controller' => 'users', 'action' => 'add'),
            array('escape' => false)
          );
          echo '</li>';
        }
      ?>
	    </ul>
	  </fieldset>
	  
	  <?php if ($this->Session->read('User.admin')) { ?>
	  <fieldset>
	    <legend>Groups</legend>
	    <ul>
	      <?php
	        echo '<li>';
        echo $this->Html->link(
          $this->Html->image('group.png').' View Groups', 
          array('controller' => 'groups', 'action' => 'index'),
          array('escape' => false)
        );
        echo '</li>';
		    
        echo '<li>';
        echo $this->Html->link(
          $this->Html->image('group_add.png').' Add Group', 
          array('controller' => 'groups', 'action' => 'add'),
          array('escape' => false)
        );
        echo '</li>';
      ?>
	    </ul>
	  </fieldset>
	  
	  <fieldset>
	    <legend>Servers</legend>
	    <ul>
	      <?php
	        echo '<li>';
        echo $this->Html->link(
          $this->Html->image('server.png').' View Servers', 
          array('controller' => 'servers', 'action' => 'index'),
          array('escape' => false)
        );
        echo '</li>';
        
        echo '<li>';
        echo $this->Html->link(
          $this->Html->image('server_add.png').' Add Server', 
          array('controller' => 'servers', 'action' => 'add'),
          array('escape' => false)
        );
        echo '</li>';
      ?>
	    </ul>
	  </fieldset>
	  <?php } ?>
	  
	  <?php if ($this->Session->read('User.admin')) { ?>
	  <fieldset>
	    <legend>Settings</legend>
	    <ul>
	     	<li>
	     	<?php 
	        echo $this->Html->link(
          $this->Html->image('permissions.png').' Manage Permissions', 
          array('controller' => 'instances', 'action' => 'index'),
          array('escape' => false)
        ); 
      ?> 
	     	</li>
		  </ul>
	  </fieldset>
	  <?php } ?>
	</div>
</div>
