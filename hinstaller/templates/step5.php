<script>
$('#step4').attr('class', 'active');
</script>

<div class="row">

  <div class="col-md-12">
    <h4>General Settings</h4>
    <p>
      This step will allow you to set a page title aswell as a thumbnail.
    </p>
    <?php
    if(!isset($success)){
      ?>
      <form class="form" method="post" action="index.php?step=5" enctype="multipart/form-data">
        <div class="form-group">
          <label>Page Title</label>
          <input type="text" class="form-control" name="page_title"  />
        </div>
        <div class="form-group">
          <label>Favicon</label> <i>(16x16px png file)</i>
          <input type="file" name="thumbnail" />
        </div>
        <input type="submit" class="btn btn-primary pull-right" />
      </form>
      <?php
    }else{
      ?>
      <p class="alert alert-success">
        Favicon and Page Title saved successfuly.
      </p>
      <a href="index.php?step=6" class="btn btn-primary pull-right">Continue</a>
      <?php
    }
     ?>
  </div>
</div>
