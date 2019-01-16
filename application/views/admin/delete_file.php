<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>Deleting File</h3>
      <div class="alert alert-danger">
        <p>You are about to delete the following file</p>
        <div class="text-center">
          <p>Filename: <strong><?php echo $file['real_name']; ?></strong></p>
          <p>Size: <?php echo formatBytes($file['filesize']); ?></p>
          <p>Uploaded: <?php echo $file['uploaded_date']; ?></p>
          <?php echo form_open('admin/files/delete/'.$file['storage_name']); ?>
          <input hidden="hidden" style="display: none" name="confirmed" value="true" />
          <input type="submit" class="btn btn-default" value="Delete file" />
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
