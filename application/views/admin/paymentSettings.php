
<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>Payment Settings</h3>
      <p></p>
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
      <br/>
      <?php echo form_open('admin/payment/settings/'.$engine['id']); ?>
        <input type="text" style="display: none; visability: hidden;" name="control" value="1" />
        <?php
        foreach($settings as $setting){
          ?>
          <div class="form-group">
            <label><?php echo $setting['display_name']; ?></label>
            <p>
              <?php echo $setting['description']; ?>
            </p>
            <input type="text" name="<?php echo $setting['name']; ?>" value="<?php echo $setting['value']; ?>" class="form-control"/>
          </div>
          <br/>
          <?php
        }
         ?>
        <input type="submit" class="btn btn-success" value="Save changes" />
      </form>
    </div>
  </div>
</div>
