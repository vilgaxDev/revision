

      <div class="upload-container">
        <p class="upload-header">Successfully uploaded!</p>
        <div class="upload-body">
          <div class="info">
            <p class="alert alert-success">Your files were successfully uploaded to our server!</p>
          </div>

          <?php
          foreach($files as $file){
            ?>
            <div class="file-item">
              <p class="file-name"><?php echo $file['name']; ?></p>
              <a class="file-path" href="<?php echo site_url('download/'.$file['storage_name']); ?>"><?php echo site_url('download/'.$file['storage_name']); ?></a>
              <p class="file-info">Automatic deletion: <?php if($file['days'] > 0) { echo 'after '.$file['days'].' days'; }else{ echo 'Never'; } ?></p>
            </div>
            <?php
          }
           ?>
        </div>
      </div>
