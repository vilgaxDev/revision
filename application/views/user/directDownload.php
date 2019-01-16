<body>
  <div class="row">
    <div class="col-sm-12 col-md-6 col-md-offset-3">
      <br />
      <div class="card">
        <br />
        <h3 class="text-center" style="text-transform: uppercase;olor: #3c3c3c;">Directly download a file</h3>
        <br />
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <table class="table">
              <tr class="row">
                <td class="col-md-5">
                  <strong>Filename</strong>
                </td>
                <td class="col-md-7">
                  <?php echo $file['real_name']; ?>
                </td>
              </tr>
              <tr class="row">
                <td class="col-md-5">
                  <strong>Filesize</strong>
                </td>
                <td class="col-md-7">
                  <?php echo formatBytes($file['filesize']); ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2 col-md-offset-5">
            <?php echo form_open('file/directDownload/'.$file['storage_name']); ?>
            <input type="text" name="download" value="true" style="display:none;"  />
            <input type="submit" class="btn btn-lg btn-success" value="Download" />
            </form>
            <br /><br />
          </div>
        </div>
      </div>
    </div>
  </div>
