<script>
$('#step5').attr('class', 'active');
</script>

<div class="row">

   <?php
   if($success == false){
     ?>
  <div class="col-md-12">
    <h4>Create Admin User</h4>
    <p>
      Create the inital admin user so you can access the Admin CP.
    </p><br />
    <form method="post" action="index.php?step=6">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Firstname</label>
            <input type="text" name="firstname" class="form-control"  />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Lastname</label>
            <input type="text" name="lastname" class="form-control"  />
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control"  />
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control"  />
          </div>
        </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Repeat Password</label>
              <input type="password" name="password_repeat" class="form-control"  />
            </div>
            <input type="submit" class="btn btn-success pull-right" value="Create User"  />
        </div>
    </form>
  </div>
</div>

<?php

}else{
  //Installation finished
  ?>
  <div class="alert alert-success">
    Admin user created successfuly.
  </div>
  <a href="index.php?step=7" class="btn btn-primary pull-right">Continue</a>
  <?php
}
?>
