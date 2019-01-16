<section class="content">
    <div class="container-fluid">
<div class="card">
  <div class="header">
        <h2>ACCOUNT DETAILS</h2>
  </div>
<div class="row body">
  <div class="col-md-12">
    <p style="font-size: 18px;">Contact Details</p>
    <table class="table">
      <tr>
        <td>
          <strong>Firstname</strong>
        </td>
        <td>
          <p><?php echo $userdetails['firstname']; ?></p>
        </td>
      </tr>
      <tr>
        <td>
          <strong>Lastname</strong>
        </td>
        <td>
          <p><?php echo $userdetails['lastname']; ?></p>
        </td>
      </tr>
      <tr>
        <td>
          Email
        </td>
        <td>
          <p><?php echo $userdetails['email']; ?></p>
        </td>
      </tr>
      <tr>
        <td>
          Password
        </td>
        <td>
          <p>******** <small><a href="<?php echo site_url('user/changepw'); ?>">(Change)</a></small></p>
        </td>
      </tr>
    </table>
    <?php

    if($premium_enabled == true){
      ?>
      <br/>
      <p style="font-size: 18px;">Account Status</p>
      <p>
        A premium subscription allows you to save more files and create more folders.
      </p>
      <?php
      //Check if the user already has a premium subscription
      if($this->authentication->premium == true){
        //Premium
        ?>
        <div class="alert alert-success">
          Your account is already a premium account.
        </div>
        <div class="row">
          <div class="col-md-6 col-md-offset-3" style="text-align: center;">
            <table class="table" style="text-align: left;">
              <tr>
                <td>
                  Purchase Date
                </td>
                <td>
                  <?php echo $premium['formatted_purchase_date']; ?>
                </td>
              </tr>
              <tr>
                <td>
                  Payment Method
                </td>
                <td>
                  <?php echo $premium['payment_method_display_name']; ?>
                </td>
              </tr>
              <tr>
                <td>
                  Valid until
                </td>
                <td>
                  <strong><?php echo $premium['formatted_valid_until']; ?></strong>
                </td>
              </tr>
            </table>
            <a href="<?php echo site_url('premium/step1'); ?>" class="btn btn-primary">Renew Subscription</a>
          </div>
        </div>
        <?php
      }else{
        //Non premium
        ?>
        <div class="row">
          <div class="col-md-7 col-md-offset-1">
            <br/>
            <div style="display: inline-block; margin-top: 20px;">
              <div class="c100 p<?php if($usage_statistics['max']['total_filesize'] == -1){ echo "1"; }else{ echo round($usage_statistics['usage']['percent']['total_filesize'], 0); } ?> small orange">
                  <span><?php if($usage_statistics['max']['total_filesize'] == -1){ echo "&infin;"; }else{ echo round($usage_statistics['usage']['percent']['total_filesize'], 0)."%"; } ?></span>
                  <div class="slice">
                      <div class="bar"></div>
                      <div class="fill"></div>
                  </div>
              </div>
              <p style="clear: both; text-align: center; padding-top: 20px;"><strong>Storage Usage</strong></p>
            </div>
            <div style="display: inline-block; margin-left: 50px;">
              <div class="c100 p<?php if($usage_statistics['max']['foldercount'] == -1){ echo "1"; }else{ echo round($usage_statistics['usage']['percent']['foldercount'], 0); } ?> small orange">
                  <span><?php if($usage_statistics['max']['foldercount'] == -1){ echo "&infin;"; }else{ echo round($usage_statistics['usage']['percent']['foldercount'], 0)."%"; } ?></span>
                  <div class="slice">
                      <div class="bar"></div>
                      <div class="fill"></div>
                  </div>
              </div>
              <p style="clear: both; text-align: center; padding-top: 20px;"><strong>Max Folders</strong></p>
            </div>

            <div style="display: inline-block; margin-left: 50px;">
              <div class="c100 p<?php if($usage_statistics['max']['filecount'] == -1){ echo "1"; }else{ echo round($usage_statistics['usage']['percent']['filecount'], 0); } ?> small orange">
                  <span><?php if($usage_statistics['max']['filecount'] == -1){ echo "&infin;"; }else{ echo round($usage_statistics['usage']['percent']['filecount'], 0)."%"; } ?></span>
                  <div class="slice">
                      <div class="bar"></div>
                      <div class="fill"></div>
                  </div>
              </div>
              <p style="clear: both; text-align: center; padding-top: 20px;"><strong>Max Files</strong></p>
            </div>

          </div>
          <div class="col-md-4" style="margin-top: 80px;">
            <a href="<?php echo site_url('premium/buy'); ?>" class="btn btn-info">Upgrade Your Account</a>
          </div>
        </div>

        <?php
      }
    }

     ?>
  </div>
</div>
</div>
</div>
</section>
