<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
      <h3>View User</h3>
      <p>
        On this page you can view at all transactions this user has taken aswell as his file statistics.
      </p>
      <br/>
      <?php
      if(!empty($errorMessage)){
        foreach($errorMessage as $message){
          ?>
          <p class="alert alert-danger"><?php echo $message; ?></p>
          <?php
        }
      }
      if(!empty($successMessage)){
        foreach($successMessage as $message){
          ?>
          <p class="alert alert-success"><?php echo $message; ?></p>
          <?php
        }
      }
       ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          User Details
        </div>
        <div class="panel-body">
          <table class="table">
            <tr class="row">
              <td class="col-md-6">
                <strong>Firstname</strong>
              </td>
              <td>
                <?php echo $user_details['firstname']; ?>
              </td>
            </tr>

            <tr class="row">
              <td class="col-md-6">
                <strong>Lastname</strong>
              </td>
              <td>
                <?php echo $user_details['lastname']; ?>
              </td>
            </tr>

            <tr class="row">
              <td class="col-md-6">
                <strong>Email Address</strong>
              </td>
              <td>
                <?php echo $user_details['email']; ?>
              </td>
            </tr>

            <tr class="row">
              <td class="col-md-6">
                <strong>Current Status</strong>
              </td>
              <td>
                <?php
                if($user_details['premium'] == 1){
                  ?><span class="label label-warning">Premium</span> (Until <?php echo $user_details['premium_until_formatted']; ?>)<?php
                }else{
                  ?><span class="label label-default">Default</span><?php
                }
                ?>

              </td>
            </tr>
          </table>
        </div>
      </div>


      <h5>Transactions</h5>
      <p>
        All transactions assosiated to the user account are displayed here.
      </p>
      <div class="panel panel-default">
        <div class="panel panel-body">
          <table class="table">
            <thead>
              <th>
                Transaction Date
              </th>
              <th>
                Amount
              </th>
              <th>
                Payment Gateway
              </th>
              <th>
                Status
              </th>
              <th>
                Action
              </th>
            </thead>
            <?php
            foreach($transactions as $transaction){
              ?>
              <tr>
                <td>
                  <?php echo date('m/d/Y h:m:a', $transaction['time']); ?>
                </td>
                <td>
                  <?php echo $transaction['amount']; ?>
                </td>
                <td>
                  <?php echo $transaction['payment_method_name']; ?>
                </td>
                <td>
                  <?php
                  if($transaction['status'] == 1){
                    ?><span class="label label-success">Completed</span><?php
                  }elseif($transaction['status'] == 0){
                    ?><span class="label label-default">Pending</span><?php
                  }elseif($transaction['status'] == 3){
                    ?><span class="label label-danger">Declined</span><?php
                  }elseif($transaction['status'] == 4){
                    ?><span class="label label-default">Refunded</span><?php
                  }
                   ?>
                </td>
                <td>
                  <?php
                  //Refund button only if payment succeeded
                  if($transaction['status'] == 1){
                    ?><a href="<?php echo site_url('admin/payment/refund/'.$transaction['id']); ?>" class="btn btn-danger btn-xs">Refund</a><?php
                  }
                   ?>
                </td>
              </tr>
              <?php
            }
             ?>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
