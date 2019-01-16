<section class="content">
    <div class="container-fluid">
          <div class="card">
            <div class="header">
              <h2>CHANGE ACCOUNT PASSWORD</h2>
            </div>
            <div class="body">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <h3>Change Password</h3>
          <?php echo form_open('user/changepw'); ?>
          <br/>
          <div class="form-group">
            <label>New Password</label>
            <div class="form-line">
              <input class="form-control" type="password" name="new_password" />
            </div>
          </div>
          <div class="form-group">
            <label>Password</label>
            <div class="form-line">
              <input class="form-control" type="password" name="repeat_new_password" />
            </div>
          </div>
          <input type="submit" class="btn btn-success" value="Change Password">
          </form>
        </div>
      </div>
            </div>
          </div>
    </div>
</section>
