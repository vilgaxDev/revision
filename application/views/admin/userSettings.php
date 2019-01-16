<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>Default User Settings</h3>
      <p>
        This page defines they settings which are applied to every user who hasn't purchased a premium package.
      </p>
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
      <?php echo form_open('admin/user/settings'); ?>
      <input type="text" name="form_submitted" value="1" hidden style="display: none; visibility: hidden" />
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Storage Settings
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
                  <p>Define storage limites for <strong>standard</strong> users. <i>Use 0 for unlimited</i></p><br/>

                  <div class="form-group form-inline">
                    <input type="text" class="form-control" name="storage_max_capacity" value="<?php echo $storage_capacity; ?>" placeholder="e.g. 1.25" style="max-width: 80px;" />
                    <label style="padding-left: 20px; ">Storage Capacitiy in GB </label>
                  </div>
                  <div class="form-group form-inline">
                    <input type="text" class="form-control" name="storage_max_folders" value="<?php echo $storage_max_folders; ?>" placeholder="e.g. 100" style="max-width: 80px;" />
                    <label style="padding-left: 20px; ">Max amount of folders</label>
                  </div>
                  <div class="form-group form-inline">
                    <input type="text" class="form-control" name="storage_max_files" value="<?php echo $storage_max_files; ?>" placeholder="e.g. 500" style="max-width: 80px;" />
                    <label style="padding-left: 20px; ">Max amount of files</label>
                  </div>
                  <br/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <input type="submit" class="btn btn-success" value="Save Changes" />
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
