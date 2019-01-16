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
        <h2>Notification Templates</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
<div class="col-xs-12">
<div class="col-xs-12">
  <?php echo form_open('admin/settings/emailTemplates'); ?>
  <input type="text" name="template_update" style="display: hidden;" value="1" hidden />
  <br/>
  <br/>
<div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
                      <div class="panel">
                        <a class="panel-heading" role="tab" id="headingOne1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1" aria-expanded="false" aria-controls="collapseOne">
                          <h4 class="panel-title">Account Management</h4>
                        </a>
                        <div id="collapseOne1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false">
                          <div class="panel-body">
                            <h4>Registration</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_registration_subject" class="form-control" value="<?php echo $templates['emailmsg_registration_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_registration_message" class="form-control"><?php echo $templates['emailmsg_registration_message']; ?></textarea>
                            </div>
                            <br/>
                            <h4>Account activated</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_account_activated_subject" class="form-control" value="<?php echo $templates['emailmsg_account_activated_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_account_activated_message" class="form-control"><?php echo $templates['emailmsg_account_activated_message']; ?></textarea>
                            </div>
                            <br/>
                            <h4>Forgot Password</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_user_forgotpw_subject" class="form-control" value="<?php echo $templates['emailmsg_user_forgotpw_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_user_forgotpw_message" class="form-control"><?php echo $templates['emailmsg_user_forgotpw_message']; ?></textarea>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="panel">
                        <a class="panel-heading" role="tab" id="headingOne1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne2" aria-expanded="false" aria-controls="collapseOne">
                          <h4 class="panel-title">Files and Folders</h4>
                        </a>
                        <div id="collapseOne2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false">
                          <div class="panel-body">
                            <h4>File Shared</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_file_shared_subject" class="form-control" value="<?php echo $templates['emailmsg_file_shared_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_file_shared_message" class="form-control"><?php echo $templates['emailmsg_file_shared_message']; ?></textarea>
                            </div>
                            <br/>
                            <h4>Folder Shared</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_folder_shared_subject" class="form-control" value="<?php echo $templates['emailmsg_folder_shared_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_folder_shared_message" class="form-control"><?php echo $templates['emailmsg_folder_shared_message']; ?></textarea>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="panel">
                        <a class="panel-heading" role="tab" id="headingOne1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne3" aria-expanded="false" aria-controls="collapseOne">
                          <h4 class="panel-title">Premium Features</h4>
                        </a>
                        <div id="collapseOne3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false">
                          <div class="panel-body">
                            <h4>Subscription Purchased</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_subscription_purchased_subject" class="form-control" value="<?php echo $templates['emailmsg_subscription_purchased_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_subscription_purchased_message" class="form-control"><?php echo $templates['emailmsg_subscription_purchased_message']; ?></textarea>
                            </div>
                            <br/>
                            <h4>Subscription About to End</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_subscription_renewalnotice_subject" class="form-control" value="<?php echo $templates['emailmsg_subscription_renewalnotice_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_subscription_renewalnotice_message" class="form-control"><?php echo $templates['emailmsg_subscription_renewalnotice_message']; ?></textarea>
                            </div>
                            <br/>
                            <h4>Subscription Terminated</h4>
                            <div class="form-group">
                              <label>Subject</label>
                              <input type="text" name="emailmsg_subscription_nvalid_subject" class="form-control" value="<?php echo $templates['emailmsg_subscription_nvalid_subject']; ?>" />
                            </div>
                            <div class="form-group">
                              <label>Text</label>
                              <textarea rows="5" name="emailmsg_subscription_nvalid_message" class="form-control"><?php echo $templates['emailmsg_subscription_nvalid_message']; ?></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br/>
                    <input type="submit" class="btn btn-success" value="Submit changes" />
                  </form>
                  <br/><br/>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<style>
.x_content h4{
  font-weight: 200 !important;
}
</style>
