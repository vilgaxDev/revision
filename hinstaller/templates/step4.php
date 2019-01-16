<script>
$('#step3').attr('class', 'active');
</script>

<div class="row">
  <?php
  if(isset($success)){
    ?>
    <div class="col-md-12">
      <p class="alert alert-success">
        Database structure created successfuly!
      </p>
    </div>
    <?php
  }
   ?>
  <div class="col-md-12">
    <h4>Create Database Structure</h4>
    <p>
      This step will insert the data into your database that is necessary to run this software.
    </p>
    <?php if(!isset($success)){ ?>
    <form action="index.php?step=4" method="post">
      <input type="text" name="submit" value="1"  style="display: none;" hidden/>
      <input type="submit" class="btn btn-success pull-right" value="Create" />
    </form>
    <?php }else{
      ?>
      <a href="index.php?step=5" class="btn btn-primary pull-right">Continue</a>
      <?php

    } ?>
  </div>
</div>
