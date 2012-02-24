<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $title_for_layout ?> - Ballista</title>
  <link href="/favicon.ico" type="image/x-icon" rel="icon" />
  <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
  <?php 
    echo $this->Html->css('cake.generic.css');
    echo $this->Html->css('ui-lightness/jquery-ui.css');
    echo $this->Html->css('style.css');
    echo $this->Html->script('jquery');
    echo $this->Html->script('jquery-ui');
    echo $this->Html->script('jquery-ui-timepicker-addon');
    echo $this->Html->script('jquery.stickytableheaders');
    echo $this->Html->script('script');  
    echo $scripts_for_layout;
  ?>
</head>

<body>
  <div id="container">
    <div id="header">
      <h1><?php echo $this->Html->link('Ballista - Code Deployment', array('controller' => 'projects', 'action' => 'index')) ?></h1>
      <div class="info">
        <?php 
          echo $this->Html->link(
            $this->Html->image('user.png') . $this->Session->read('User.username'),
            array('controller' => 'users', 'action' => 'view', $this->Session->read('User.id')), 
            array('escape' => false)
          );

          echo '<span class="divide">|</span>';

          echo $this->Html->link(
            $this->Html->image('logout.png').' Logout', 
            array('controller' => 'users', 'action' => 'logout'), 
            array('escape' => false)
          );
        ?> 
      </div>
    </div>

    <div id="flashHolder"><?php echo $session->flash() ?></div>

    <div id="content">
      <?php echo $content_for_layout; ?>
    </div>
  </div>
  
  <div id="license">Licensed under <a href="http://www.gnu.org/licenses/gpl-3.0-standalone.html" target="_blank">GPL v3</a></div>
  
  <?php echo $this->element('sql_dump'); ?>
  
  <?php echo $this->Js->writeBuffer(); ?>
  
  <div id="fade" class="blackOverlay"></div>
</body>
</html>
