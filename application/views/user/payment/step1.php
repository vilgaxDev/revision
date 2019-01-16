<section class="content">
    <div class="container-fluid">
        <div class="block-header">
              <h2>Become a premium user</h2>
        </div>
    <div class="card">
      <div class="header">
            <h2>Overview</h2>
      </div>
      <div class="body">
        <p>
          Purchase a premium subscription to enjoy more features:
          <table class="table">
            <tr style="text-align: center;">
              <td style="text-align: left">
                <strong>Package</strong>
              </td>
              <td>
                <strong>Standard</strong>
              </td>
              <td>
                <strong style="color: #F44336">Premium</strong>
              </td>
            </tr>
            <tr style="text-align: center;">
              <td style="text-align: left">
                <strong>Storage Volume</strong>
              </td>
              <td>
                <?php echo $features['storage_capacity']; ?>GB
              </td>
              <td>
                <?php echo $features['premium_storage_capacity']; ?>GB
              </td>
            </tr>
            <tr style="text-align: center;">
              <td style="text-align: left">
                <strong>Max Folders</strong>
              </td>
              <td>
                <?php if($features['storage_max_folders'] == -1){
                  echo 'unlimited';
                }else{
                  echo $features['storage_max_folders'];
                } ?>
              </td>
              <td>
                <?php if($features['premium_storage_max_folders'] == -1){
                echo 'unlimited'; }else{
                  echo $features['premium_storage_max_folders'];
                } ?>
              </td>
            </tr>
            <tr style="text-align: center;">
              <td style="text-align: left">
                <strong>Max Files</strong>
              </td>
              <td>
                <?php if($features['storage_max_files'] == -1){
                  echo 'unlimited';
                }else{
                  echo $features['storage_max_files'];
                } ?>
              </td>
              <td>
                <?php if($features['premium_storage_max_files'] == -1){
                echo 'unlimited'; }else{
                  echo $features['premium_storage_max_files'];
                } ?>
              </td>
            </tr>
            <tr>
              <td style="text-align: left;"><strong>Price</strong></td>
              <td style="text-align: center;">Free</td>
              <td style="color: #F44336; text-align: center;">
                  Only <strong><?php echo $features['premium_price'].$features['premium_currency']; ?></strong>
              </td>
            </tr>
          </table>
        </p>
      <div class="row">
        <div class="col-md-8" style="vertical-align: bottom; float: none; display: table-cell">
        <p style="font-size: 16px; padding-bottom: 0; margin-top: 25px">Please Choose a payment service</p>
        <fieldset>
        <?php echo form_open('premium/step2');
        foreach($payment_services as $service){
          ?>
            <input type="radio" id="payment_service<?php echo $service['id']; ?>" name="payment_service" value="<?php echo $service['id']; ?>">
            <label for="payment_service<?php echo $service['id']; ?>">
              <img style="max-height: 20px; height: auto; max-width: 20px;" src="<?php echo $service['logo_url'] ?>" />
              <?php echo $service['public_display_name']; ?>
            </label><br>
          <?php
        }
         ?>
         </fieldset>
        <p class="clearfix"></p>
        <div id="payment_duration" style="display: none;">
          <p style="font-size: 16px; padding-bottom: 0; margin-top: 25px">Select a payment duration</p>
          <fieldset>
          <?php
          foreach($payment_durations as $duration){
            ?>
            <input type="radio" id="duration<?php echo $duration['months']; ?>" data-discount="<?php echo $duration['discount']; ?>" name="duration" value="<?php echo $duration['months']; ?>" />
            <label for="duration<?php echo $duration['months']; ?>">
              <?php echo $duration['months']; ?> Months
              <?php
              if($duration['discount'] > 0){
                ?><p class="label label-success">-<?php echo $duration['discount']; ?>% </p><?php
              }
               ?>
            </label><br/>
            <?php
          }
           ?>
          </fieldset>
        </div>
        </div>
        <div class="summary col-md-4" style="vertical-align: bottom; float: right; display: table-cell">
          <h4>Total: <span style="color: #ee6b01" id="total_price">0.00</span><span style="color: #ee6b01"> <?php echo $features['premium_currency']; ?></span> <input type="submit" class="btn btn-default disabled pull-right" value="Checkout"/></h4>

        </div>
      </div>
        </form>
      </div>
    </div>
</div>
</section>
<script src="<?php echo base_url();?>public/new/plugins/jquery/jquery.min.js"></script>
<script>
  $("input[name='payment_service']").change(function(){
    $('#payment_duration').show();
  });
  var price_m = <?php echo $features['premium_price']; ?>;
  $("input[name='duration']").change(function(){
    var months = $(this).val();
    var price = months * price_m;
    var discount = $(this).attr('data-discount');
    price = price - (price*discount/100);
    price = price.toFixed(2);
    $('#total_price').html(price);
    $("input[type='submit']").removeClass('disabled btn-default');
    $("input[type='submit']").addClass('btn-primary');
  })
</script>
