<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Storage Engines</h3>
      </div>

    </div>

<div class="clearfix"></div>



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><?php echo $engine['display_name']; ?></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
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
        <?php
        echo form_open('admin/storage/settings/'.$engine['library_name']);
        foreach($engine_settings as $setting){
          ?>
          <div class="form-group">
            <?php
            switch ($setting['type']) {
              case 'text':
                echo '<label>'.$setting['label'].'</label>';
                echo "<input type='text' value='".$setting['value']."' name='".$setting['name']."' class='form-control' />";
                break;
            }
             ?>
          </div>
          <?php
        }
         ?>
         <input type="submit" class="btn btn-success pull-right" value="Submit" />
         <br/>
       </form>
   </div>
</div></div></div></div></div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
