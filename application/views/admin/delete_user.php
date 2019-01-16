<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>Delete User Account</h3>
      <div class="alert alert-danger">
        <p>You are about to <strong>permanently delete</strong> the following user including all its files, folders and transaction history:</p><br/>
        <div class="text-center">
          <p>Email: <strong><?php echo $user_delete['email']; ?></strong></p>
          <p>Name: <?php echo $user_delete['firstname'].' '.$user_delete['lastname']; ?></p>
          <?php echo form_open('admin/user/delete/'.$user_delete['id']); ?>
          <input hidden="hidden" style="display: none" name="confirmed" value="true" />
          <input type="submit" class="btn btn-default" value="Delete file" />
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
