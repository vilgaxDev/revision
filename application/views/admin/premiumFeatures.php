<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>Premium Features</h3>
      <p>Premium Features allow you to charge clients for an add free dashboard with increased storage capacities.</p><br/>
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
      <br/>
      <div class="row">
        <div class="col-md-12">
          <p style="font-weight: bold; color: green; display: none;" id="premium_success_msg"></p>
        </div>
        <div class="col-md-6">
          <p>Enable Premium Features</p>
        </div>
        <div class="col-md-6">
          <label class="pull-right">
            <input type="checkbox" <?php if($premium_enabled == true){ echo 'checked data-toggle="toggle"'; } ?> id="premium-checkbox" data-size="mini" data-onstyle="success" data-offstyle="danger">
          </label>
        </div>
      </div>
      <?php echo form_open('admin/premium'); ?>
      <input type="text" name="form_submitted" value="1" hidden="" style="display: none; visibility: hidden;"/>
      <div class="row">
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              Product Details
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Price per month</label>
                    <input type="text" class="form-control" name="product_price" value="<?php echo $premium_price; ?>" placeholder="e.g. 20.50"/>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Currency Code</label>
                    <input type="text" class="form-control" name="product_currency" value="<?php echo $premium_currency; ?>" maxlength="3" placeholder="e.g. USD" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Storage Settings
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    <p>Define storage limites for premium users. <i>Use 0 for unlimited</i></p><br/>

                    <div class="form-group form-inline">
                      <input type="text" class="form-control" name="storage_max_capacity" value="<?php echo $premium_storage_capacity; ?>" placeholder="e.g. 1.25" style="max-width: 80px;" />
                      <label style="padding-left: 20px; ">Storage Capacitiy in GB </label>
                    </div>
                    <div class="form-group form-inline">
                      <input type="text" class="form-control" name="storage_max_folders" value="<?php echo $premium_storage_max_folders; ?>" placeholder="e.g. 100" style="max-width: 80px;" />
                      <label style="padding-left: 20px; ">Max amount of folders</label>
                    </div>
                    <div class="form-group form-inline">
                      <input type="text" class="form-control" name="storage_max_files" value="<?php echo $premium_storage_max_files; ?>" placeholder="e.g. 500" style="max-width: 80px;" />
                      <label style="padding-left: 20px; ">Max amount of files</label>
                    </div>
                    <br/>
                  </div>
                  <!--<div class="col-md-5">
                    <div class="form-group">
                      <label>Max downloads</label>
                      <input type="text" name="storage_max_downloads" class="form-control" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label>Per</label>
                    <select name="storage_max_downloads_classifier" class="form-control">
                      <option value="f">File</option>
                      <option value="a">Account</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Time Intervall</label>
                    <select name="storage_max_downloads_intervall" class="form-control">
                      <option value="h">Hourly</option>
                      <option value="d">Daily</option>
                      <option value="w">Weekly</option>
                      <option value="m">Monthly</option>
                    </select>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <input type="submit" class="btn btn-success" value="Save Changes" />
        </div>

      </div>

    </div>
  </div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
<script>
$('#premium-checkbox').bootstrapToggle({
  on: 'On',
  off: 'Off'
});

$('#premium-checkbox').change(function(){
  $('#premium_success_msg').html(' ');
  $('#premium_success_msg').show();
  $.get("<?php echo site_url('admin/premium/enable_premium'); ?>");
  $('#premium_success_msg').html('Changes saved successfuly');
  $('#premium_success_msg').delay(2000).fadeOut('slow');

  setTimeout(function() {
    $('#premium_success_msg').html(' ');
    $('#premium_success_msg').show();
  }, 3500); // <-- time in milliseconds
});
</script>
