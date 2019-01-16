
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>List All Users</h3>
      </div>

    </div>
  </div>

<div class="clearfix"></div>



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Overview</h2>
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
<div class="row">
      <div class="col-md-3 col-md-offset-6">
          <div id="custom-search-input">
              <?php echo form_open('admin/user', array('class' => 'input-group col-md-12', 'method' => 'GET')); ?>
                  <input name="term" type="text" class="form-control" placeholder="Search for users" />
                  <input name="page" type="text" value="<?php echo $page; ?>" hidden/>
                  <span class="input-group-btn">
                      <button class="btn" type="submit">
                          <i class="glyphicon glyphicon-search"></i>
                      </button>
                  </span>
              </form>
          </div>
      </div>
</div>
<br/>
<div class="table-responsive">
  <table class="table">
    <thead>
      <th class="col-md-1">#</th>
      <th class="col-md-2">Firstname</th>
      <th class="col-md-2">Lastname</th>
      <th class="col-md-2">Email</th>
      <th class="col-md-1">Premium</th>
      <th class="col-md-5 text-center">Action</th>
    </thead>
    <tbody>
      <?php
      foreach($users as $user){
        ?>
        <tr>
          <td class="col-md-1"><?php echo $user['id']; ?></td>
          <td class="col-md-2"><?php echo $user['firstname']; ?></td>
          <td class="col-md-2"><?php echo $user['lastname']; ?></td>
          <td class="col-md-2"><?php echo $user['email']; ?></td>
          <td class="col-md-1"><?php
          if($user['premium'] == 1){
            ?>
            <span class="label label-warning">Premium</span>
            <?php
          }else{
            ?><span class="label label-default">Standard</span><?php
          }
           ?></td>
          <td class="col-md-5">
            <?php if($this->authentication->has_permission('edit_user', false) == TRUE){?>
            <a href="<?php echo site_url('admin/user/delete/'.$user['id']); ?>" class="pull-right btn btn-danger btn-xs" style="margin-left: 10px;">Delete</a>
            <?php } ?>
            <?php
            if($user['active'] == 1){
              ?>
              <a href="<?php echo site_url('admin/user/suspend/'.$user['id']); ?>" class="pull-right btn btn-warning btn-xs" style="margin-left: 10px;">Suspend</a>
              <?php
            }else{
              ?>
              <a href="<?php echo site_url('admin/user/suspend/'.$user['id']); ?>" class="pull-right btn btn-success btn-xs" style="margin-left: 10px;">Activate</a>
              <?php
            }
             ?>
            <a href="<?php echo site_url('admin/user/edit/'.$user['id']); ?>" class="pull-right btn btn-info btn-xs" style="margin-left: 10px;">Edit</a>
            <a href="<?php echo site_url('admin/user/view/'.$user['id']); ?>" class="pull-right btn btn-default btn-xs" style="margin-left: 10px;">View</a>
          </td>
        </tr>
        <?php
      }
      ?>
    </tbody>
  </table>
</div>
<nav>
  <ul class="pagination pull-right">
    <li>
      <a href="<?php echo site_url('admin/user?page='.($page - 1).'&term='.$search_term); ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php
    for($i = 1; $i <= $total_pages; $i++){
      echo '<li><a href="'.site_url('admin/user?page='.$i.'&term='.$search_term).'">'.$i.'</a></li>';
    }
     ?>
    <li>
      <a href="<?php echo site_url('admin/user?page='.($page + 1).'&term='.$search_term); ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
