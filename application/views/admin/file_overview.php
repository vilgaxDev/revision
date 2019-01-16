<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
    <h3>File Overview</h3>
    <p>On this page you can see all files uploaded to your servers. You can search for specific files using the searchbar on the top left,<br/>or you can order them using the panel on the top right. You can also delete
    files if you want to.</p>
    <br/>
    <?php
    if($this->session->has_userdata('success_message')){
      ?>
      <div class="alert alert-success">
        <p><?php echo $this->session->userdata('success_message'); $this->session->unset_userdata('success_message'); ?></p>
      </div>
      <?php
    }
     ?>
    <div class="row">
      <div class="col-md-2">
        <?php echo form_open('admin/files/overview/'.$current_page, array('method' => 'get')); ?>
          <div class="row">
            <div class="col-md-10">
              <input type="text" class="form-control" placeholder="Search..." value="<?php echo $search_term; ?>" name="search_term" />
            </div>
            <div class="col-md-2">
              <input type="submit" class="btn btn-sm btn-success" value="Search" />
            </div>
          </div>
      </div>
      <div class="col-md-2 col-md-offset-8">
          <div class="row">
            <div class="col-md-12">
              <select class="form-control" name="order_by" onchange='if(this.value != 0) { this.form.submit(); }'>
                <option value="udnf" <?php if($order_by == 'udnf'){echo "selected='selected'";} ?>>Upload date (newest first)</option>
                <option value="udof" <?php if($order_by == 'udof'){echo "selected='selected'";} ?>>Upload date (oldest first)</option>
                <option value="fssf" <?php if($order_by == 'fssf'){echo "selected='selected'";} ?>>Filesize (smallest first)</option>
                <option value="fsbf" <?php if($order_by == 'fsbf'){echo "selected='selected'";} ?>>Filesize (biggest first)</option>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
    <br/>
    <div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <th>File Name</th>
      <th>Upload Date</th>
      <th>Filesize</th>
      <th>Storage Engine</th>
      <th>Actions</th>
    </thead>
    <tbody>
      <?php
      foreach($files as $file){
        ?>
        <tr>
          <td><?php echo $file['real_name']; ?></td>
          <td><?php echo $file['uploaded_date']; ?></td>
          <td><?php echo formatBytes($file['filesize']); ?></td>
          <td><?php echo $file['storage_engine_name']; ?></td>
          <td><a href="<?php echo site_url('admin/files/delete/'.$file['storage_name']); ?>" class="btn btn-danger btn-sm">Delete</a></td>
        </tr>
        <?php
      }
       ?>
    </tbody>
  </table>
</div>
  <nav aria-label="Page navigation" class="pull-right">
  <ul class="pagination">
    <li>
      <a href="<?php echo site_url('admin/files/overview/'.($current_page-1)); ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php
    for($x=1;$x<$total_pages+1;$x++){
      ?>
      <li><a href="<?php echo site_url('admin/files/overview/'.($x)); ?>"><?php echo($x); ?></a></li>
      <?php
    }
     ?>
    <li>
      <a href="<?php echo site_url('admin/files/overview/'.($current_page+1)); ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
</div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
