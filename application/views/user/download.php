

      <div class="upload-container">
        <p class="upload-header">Download now!</p>
        <div class="upload-body download text-center">
          <div class="content">
            <p><strong class="file-name"><?php echo $file['real_name']; ?></strong></p>
            <p>Filesize: <strong><?php echo $file['filesize']; ?></strong></p>
            <a href="<?php echo site_url('download/startDownload/'.$file['storage_name']); ?>" class="red-button">Download now</a>
            <br/><br/>
          </div>
        </div>
      </div>
