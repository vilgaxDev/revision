<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Settings</h3>
      </div>

    </div>

<div class="clearfix"></div>



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Email Server</h2>
        <div class="clearfix"></div>
      </div>
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
      <div class="x_content">
        <div class="row">
            <?php
            if($this->session->has_userdata('errorMessage')){
              ?>
              <div class="alert alert-danger">
                <p><?php echo $this->session->userdata('errorMessage'); $this->session->unset_userdata('errorMessage'); ?></p>
              </div>
              <?php
            }
             ?>
           </div>
<div class="col-xs-12">
  <?php echo form_open('admin/settings/emailSettings'); ?>
  <div class="pull-right">
    <span>Enable Notifications </span>

    <?php
    if($email_notifications == TRUE){
      ?>
      <input type="checkbox" <?php if($email_notifications == true){ echo 'checked data-toggle="toggle"'; } ?> name="enableNotifications" data-size="mini" data-onstyle="success" data-offstyle="danger">
      <?php
    }else{
      ?>
      <input type="checkbox" <?php if($email_notifications == true){ echo 'checked data-toggle="toggle"'; } ?> name="enableNotifications" data-size="mini" data-onstyle="success" data-offstyle="danger">
      <?php
    }
    ?>
  </div>
  <br/>
  <div class="form-group">
    <label>Hostname</label>
    <input type="text" name="email_hostname" class="form-control" value="<?php echo $email_hostname; ?>" />
  </div>
  <div class="form-group">
    <label>Username</label>
    <input type="text" name="email_username" class="form-control" value="<?php echo $email_username; ?>" />
  </div>
  <div class="form-group">
    <label>Password</label>
    <input type="text" name="email_password" class="form-control" value="<?php echo $email_password; ?>" />
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Email Address</label>
        <input type="text" name="email_address" class="form-control" value="<?php echo $email_address; ?>" />
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label>Email Display Name</label>
        <input type="text" name="email_display_name" class="form-control" value="<?php echo $email_display_name; ?>" />
      </div>
    </div>
  </div>
  <input type="submit" class="btn btn-success" value="Submit changes" />
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
$('document').ready(function(){
  $("input[name='enableNotifications']").bootstrapToggle({
    on: 'On',
    off: 'Off'
  });
  $("input[name='enableNotifications']").change(function(){
    $('#email_notifications').html(' ');
    $('#email_notifications').show();
    $.get("<?php echo site_url('admin/settings/emailEnable'); ?>");
    $('#email_notifications').html('Changes saved successfuly');
    $('#email_notifications').delay(2000).fadeOut('slow');
    setTimeout(function(){
      location.reload()
    },500);
});
});
</script>
