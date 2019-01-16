<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>General Settings</h3>
      <p>This page allowes you to define all settings which don't fit into any other major category.</p>
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
       <div class="row">
         <div class="col-md-12">
           <?php echo form_open_multipart('admin/settings/general'); ?>
            <div class="form-group">
              <label>Page Title</label>
              <input type="text" name="page_title" class="form-control" value="<?php echo $settings['page_title']; ?>" />
            </div>
            <div class="form-group">
              <label>Favicon</label><br />
              <img style="display: inline-block; height: 16px; width: 16px;" src="data:image/png;base64,<?php echo $settings['favicon'] ?>"  /><input style="display: inline-block; padding-left: 10px" type="file" name="page_favicon" />
            </div>
            <input type="submit" class="pull-right btn-primary btn"  />
          </form>
         </div>
       </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
