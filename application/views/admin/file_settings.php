<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>Upload Settings</h3>
      <p>Here you can define whitelists / blacklists for file types. <br/>You can also set the max file size and the upload limit per user in a specific timeframe</p>
      <br/>
      <?php
      if(!empty($errorMessage)){
        foreach($errorMessage as $message){
          ?>
          <p class="alert alert-danger"><?php echo $message; ?></p>
          <?php
        }
      }
      if(!empty($successMessage)){
        foreach($successMessage as $message){
          ?>
          <p class="alert alert-success"><?php echo $message; ?></p>
          <?php
        }
      }
       ?>
      <div class="alert alert-info">
        Only the whitelist or blacklist can be active. You can't enable both at the same time.
      </div>
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              Whitelist
              <label class="checkbox-inline pull-right">

                <input type="checkbox" <?php if($whitelist_enabled == true){ echo 'checked data-toggle="toggle"'; } ?> id="whitelist-checkbox" data-size="mini" data-onstyle="success" data-offstyle="danger">
              </label>
              <strong id="whitelist_success_msg" class="pull-right" style="padding-right: 10px; color: green"></strong>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
                <table class="table">
                <?php
                foreach($whitelist as $list){
                  ?>
                  <tr>
                    <td><?php echo $list['content']; ?></td>
                    <td><a href="<?php echo site_url('admin/fileupload/delete/whitelist/'.$list['id']); ?>" class="btn btn-danger btn-xs pull-right">Delete</a></td>
                  </tr>
                  <?php
                }
                 ?>
                </table>
                </div>
              </div>
              <?php echo form_open('admin/fileupload/settings', array('class' => 'text-center')) ?>
                <input type="text" class="display: none;" hidden="hidden" name="type" value="whitelist_item" />
                <div class="form-inline">
                  <div class="form-group">
                    <input type="text" class="form-control" name="whitelist_item" placeholder="MIME Type"/>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Add entry" style="margin-bottom: 0"/>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              Blacklist
              <label class="checkbox-inline pull-right">

                <input type="checkbox" <?php if($blacklist_enabled == true){ echo 'checked data-toggle="toggle"'; } ?> id="blacklist-checkbox" data-size="mini" data-onstyle="success" data-offstyle="danger">
              </label>
              <strong id="blacklist_success_msg" class="pull-right" style="padding-right: 10px; color: green"></strong>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
                <table class="table">
                <?php
                foreach($blacklist as $list){
                  ?>
                  <tr>
                    <td><?php echo $list['content']; ?></td>
                    <td><a href="<?php echo site_url('admin/fileupload/delete/blacklist/'.$list['id']); ?>" class="btn btn-danger btn-xs pull-right">Delete</a></td>
                  </tr>
                  <?php
                }
                 ?>
                </table>
                </div>
              </div>
              <?php echo form_open('admin/fileupload/settings', array('class' => 'text-center')) ?>
                <input type="text" class="display: none;" hidden="hidden" name="type" value="blacklist_item" />
                <div class="form-inline">
                  <div class="form-group">
                    <input type="text" class="form-control" name="blacklist_item" placeholder="MIME Type"/>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Add entry" style="margin-bottom: 0"/>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
<script>
$('#whitelist-checkbox').bootstrapToggle({
  on: 'On',
  off: 'Off'
});
$('#blacklist-checkbox').bootstrapToggle({
  on: 'On',
  off: 'Off'
});
    $('#whitelist-checkbox').change(function(){
      $('#whitelist_success_msg').html(' ');
      $('#whitelist_success_msg').show();
      $.get("<?php echo site_url('admin/fileupload/enable_whitelist'); ?>");
      $('#whitelist_success_msg').html('Changes saved successfuly');
      $('#whitelist_success_msg').delay(2000).fadeOut('slow');
      setTimeout(function(){
        location.reload()
      },2000);

      setTimeout(function() {
        $('#whitelist_success_msg').html(' ');
        $('#whitelist_success_msg').show();
      }, 3500); // <-- time in milliseconds
    });

    $('#blacklist-checkbox').change(function(){
      $('#blacklist_success_msg').html(' ');
      $('#blacklist_success_msg').show();
      $.get("<?php echo site_url('admin/fileupload/enable_blacklist'); ?>");
      $('#blacklist_success_msg').html('Changes saved successfuly');
      $('#blacklist_success_msg').delay(2000).fadeOut('slow');
      setTimeout(function(){
        location.reload()
      },2000);

      setTimeout(function() {
        $('#blacklist_success_msg').html(' ');
        $('#blacklist_success_msg').show();
      }, 3500); // <-- time in milliseconds
    });
</script>
