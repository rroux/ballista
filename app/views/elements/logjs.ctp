<script type="text/javascript">
  <?php if (isset($dispatch)) { ?>
    // If user requested a deploy dispatch, then execute via ajax request
    $.ajax('<?php echo $this->webroot ?>logs/shellexec');  
  <?php } ?>

  /*
   * Function that performs an ajax
   * request to refresh this page every x seconds
   */
  function refreshLogs() {
    $.ajax({
        url: '<?php echo $this->webroot ?>logs/index/<?php echo $this->params['pass'][0] ?>', 
        success: function(data){
          $('#content').html(data);
        }
    });
  }

  $(document).ready(function() {
    // Refresh this page every x seconds if any process is running
    if($('.running').length != 0){
      setTimeout('refreshLogs()', 7000);
    }
    
    // Setup status buttons to show commit output in lightboxes
    $('.logWindow').click(function() {
      var ele = $(this).attr('id');
      $('#fade').show();
      $('#log'+ele).show('fast');
    });

    // Close lightbox if clicked outside lightbox
    $('#fade').click(function() {
      $('.lightBox').hide();
      $('#fade').hide();
    });
    
    // Close lightbox on button click
    $('.closeButton').click(function() {
      $(this).parent().hide();
      $('#fade').hide();
    });
  });
</script>