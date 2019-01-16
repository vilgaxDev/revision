<script>
$('#step2').attr('class', 'active');
</script>

<div class="row">
  <div class="col-md-12">
    <h4>Verify Requirements</h4>
    <p>
      Verify that your system meets the minimum requirements for this software.
    </p>
    <br />
    <?php
    $false = true;
    //Check if CURL is enabled
    if(class_exists('PDO') == TRUE){
      ?>
      <div class="alert alert-success">
        MySQL PDO is enabled on this server
      </div>
      <?php
    }else{
      $false = false;
      ?>
      <div class="alert alert-warning">
        MySQL PDO is not enabled on this server
      </div>
      <?php
    }
    //Check the php version
    if(phpversion() > 5.4){
      ?>
      <div class="alert alert-success">
        The server is running PHP 5.4.0 or higher
      </div>
      <?php
    }else{
      $false = false;
      ?>
      <div class="alert alert-danger">
        PHP 5.4.0 or higher is required for this application to work properly!
      </div>
      <?php
    }
    //Check if application/uploads is writable
    if(is_writable('../application/uploads')){
      ?>
      <div class="alert alert-success">
        ./application/uploads is writable!
      </div>
      <?php
    }else{
      $false = false;
      ?>
      <div class="alert alert-danger">
        ./application/uploads is not writable! File uploads will fail!
      </div>
      <?php
    }
    //Check if application/config is writable
    if(is_writable('../application/config')){
      ?>
      <div class="alert alert-success">
        ./application/config is writable!
      </div>
      <?php
    }else{
      $false = false;
      ?>
      <div class="alert alert-danger">
        ./application/config is not writable! The system won't work under these conditions!
      </div>
      <?php
    }
    ?>
  </div>
  <div class="col-md-12">
    <?php
    if($false ==true){
      ?>
      <a href="index.php?step=3" class="btn btn-primary pull-right">Continue</a>
      <?php
    }
     ?>
  </div>

</div>
