<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Settings</h3>
      </div>

    </div>

<div class="clearfix"></div>



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Storage Engines</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
     <table class="table">
                       <thead>
                         <tr>
                           <th>Type</th>
                           <th>Public Name</th>
                           <th>Storage Usage</th>
                           <th>Status</th>
                           <th> </th>
                         </tr>
                       </thead>
                       <tbody>
                         <?php
                         foreach($storage_engines as $engine){
                           ?>
                           <div class="col-xs-12">
                             <tr>
                               <td style="vertical-align: middle;"><?php echo ucfirst($engine['library_name']); ?></td>
                               <td style="vertical-align: middle;"><?php echo $engine['display_name']; ?></td>
                               <td style="vertical-align: middle;">
                                 <?php
                                 if($engine['storage_usage'] == -1){
                                   ?>
                                   <p><i>No limit set.</i></p>
                                   <?php
                                 }else{
                                   ?>
                                   <div class="progress" style="max-width: 200px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $engine['storage_usage']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $engine['storage_usage']; ?>" style="width: <?php echo $engine['storage_usage']; ?>%; min-width: 4em;">
                                      <?php echo $engine['storage_usage']; ?>%
                                    </div>
                                  </div>
                                   <?php
                                 }
                                  ?>
                               </td>
                               <td style="vertical-align: middle;"><?php
                               if($engine['active'] == TRUE){
                                 ?>
                                 Enabled <a class="btn btn-xs btn-danger" href="#">Disable</a>
                                 <?php
                               }else{
                                 ?>
                                 Disabled <a class="btn btn-xs btn-success" href="<?php echo site_url('admin/storage/enableStorageEngine/'.$engine['library_name']); ?>">Enable</a>
                                 <?php
                               }
                                ?></td>
                                <td style="vertical-align: middle;"><a href="<?php echo site_url('admin/storage/settings/'.$engine['library_name']); ?>">Settings</a></td>
                             </tr>
                           </div>
                           <?php
                         }
                         ?>
                       </tbody>
                     </table>
   </div>
</div></div></div></div></div>
