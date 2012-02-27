<script type="text/javascript">
  var deployMsg = '<span class="deploy">&#171; Deploy this version</div>';
  var rollbackMsg = '<span class="deploy">&#171; Rollback to this version</div>';

  $(document).ready(function() {

    // If all-servers checkbox is selected
    $('#allservers').click(function() {
      // Turn on all the individual server checkboxes
      $('#instancesTable').find(':checkbox').attr('checked', this.checked);

      // Highlight all the serverboxes if chekcbox was checked
      if(this.checked) {
        $('#instancesTable').find('.commitInfo').css({
          opacity : 1
        });
        $('#instancesTable').find('.serverBox .text').css({
          opacity : 1
        });

        // Dim all the serverboxes if checkbox was unchecked
      } else {
        $('#instancesTable').find('.commitInfo').css({
          opacity : 0.2
        });
        $('#instancesTable').find('.serverBox .text').css({
          opacity : 0.2
        });
      }
    });

    // If one of the server's checkboxes is clicked
    $('#instancesTable').find('input:checkbox').click(function() {
      // Turn off the all-servers checkbox
      $('#allservers').attr('checked', false);

      // Highlight the chosen serverbox if checkbox was checked
      if(this.checked) {
        $(this).parents('td').children('.commitInfo').css({
          opacity : 1
        });
        $(this).parents('td').find('.serverBox .text').css({
          opacity : 1
        });

        // Dim the chosen serverbox if checkbox was unchecked
      } else {
        $(this).parents('td').children('.commitInfo').css({
          opacity : 0.2
        });
        $(this).parents('td').find('.serverBox .text').css({
          opacity : 0.2
        });
      }
    });

    // Set datepicker on time fields
    $('.timefield').datetimepicker({
      dateFormat : 'yy-mm-dd'
    });

    // Gray out the default deploy comment text
    $('.actionBox > .text > input').css({
      color : '#CCC'
    });
    $('.actionBox > .text > input').focus(function() {
      $(this).val('');
      $(this).css({
        color : '#000'
      });
    });
    // Turn on first radio button in commits list
    firstRadioOn();

    // Add text to row when its respective radio button is chosen
    $('.radio').live('click', function() {
      $('.deploy').empty();
      // If parent of parent element has classname old, then its a rollback
      if($(this).parents().eq(1).attr('class').indexOf('old') != -1) {
        $('.radio:checked').parent().not(':contains("Current")').append(rollbackMsg);
      } else {
        $('.radio:checked').parent().not(':contains("Current")').append(deployMsg);
      }
    });

    // Show notification fields if Send mail checkbox is clicked
    $('#sendmail').click(function() {
      $('#mailBox').toggle('fast');
    });

    // Stop deploy form submission if no servers are selected
    $('#InstanceExecuteForm').submit(function(e) {
      if(!$('input[type=checkbox]:checked').length) {
        e.preventDefault();
        alert('Cannot execute. No servers were selected.');
      }
    });

    // Toggle history view
    $('.historyTitle').live('click', function() {
      $(this).next().toggle();
    });
  });

  // Function to check and highlight first radio field for deploy by default
  function firstRadioOn() {
    $('.commitInfo .logitem:first-child input').attr('checked', 'checked');
    $('.radio:checked').parent().not(':contains("Current")').append(deployMsg);
  }
</script>