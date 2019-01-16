<script>
$('#step3').attr('class', 'active');
</script>

<div class="row">
  <div class="col-md-12">
    <?php
    if($success == true){
      ?>
      <p class="alert alert-success">
        Connected to the database successfuly.
      </p>
      <?php
    }
    ?>
  </div>
  <div class="col-md-12">
    <h4>Database Connection</h4>
    <p>
      Verify that your system meets the minimum requirements for this software.
    </p>
    <br />
  </div>
  <?php
  if(!$success == true){
    ?>
  <div class="col-md-12">
    <form class="form" method="post" action="index.php?step=3">
      <div class="row">
        <div class="form-group col-md-6" style="display: inline-block;">
          <div>
            <label>Hostname</label>
            <input type="text" name="host" class="form-control"  />
          </div>
        </div>
        <p class="col-md-6" style="padding-top: 30px; font-style: italic;">
          localhost, or an external database host
        </p>
      </div>

      <div class="row">
        <div class="form-group col-md-6" style="display: inline-block;">
          <div>
            <label>Username</label>
            <input type="text" name="user" class="form-control"  />
          </div>
        </div>
        <p class="col-md-6" style="padding-top: 30px; font-style: italic;">
          Database Username
        </p>
      </div>

      <div class="row">
        <div class="form-group col-md-6" style="display: inline-block;">
          <div>
            <label>Password</label>
            <input type="text" name="password" class="form-control"  />
          </div>
        </div>
        <p class="col-md-6" style="padding-top: 30px; font-style: italic;">
          Password for the corresponding user
        </p>
      </div>

      <div class="row">
        <div class="form-group col-md-6" style="display: inline-block;">
          <div>
            <label>Database Name</label>
            <input type="text" name="database" class="form-control"  />
          </div>
        </div>
        <p class="col-md-6" style="padding-top: 30px; font-style: italic;">
          Database the user has read and write premissions to
        </p>
      </div>
<input type="submit" class="btn btn-success pull-right" value="Verify Connection" /><?php
      }else{
        ?><a href="index.php?step=4" class="btn btn-primary pull-right">Continue</a><?php
      }
       ?>
    </form>
  </div>
</div>
