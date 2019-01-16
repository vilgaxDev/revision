
<div class="right_col" role="main">
  <div class="x_content">
    <div class="x_panel">
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
      <h3>Payment Providers</h3>
      <p>You can process payments using one or multiple payment gateways.<br/>
       As soon as configured simple enable them and your customers will be able to pay their premium features using the enabled gateway.</p>
      <br/>
      <table class="table">
        <thead>
          <th class="col-md-8">Service Name</th>
          <th class="col-md-2">Status</th>
          <th class="col-md-2">Action</th>
        </thead>
        <tbody>
          <?php
          foreach($payment_engines as $payment_engine){
            ?>
            <tr>
              <td class="col-md-8"><?php echo $payment_engine['display_name']; ?></td>
              <td class="col-md-2">
              <?php
              if($payment_engine['active'] == 1){
                ?>
                <a class="btn btn-xs disabled btn-success">Enabled</a>
                <?php
              }else{
                ?>
                <p class="btn btn-xs disabled btn-danger">Disabled</p>
                <?php
              }
               ?>
             </td>
             <td class="col-md-2">
               <?php
               if($payment_engine['active'] == 1){
                 ?>
                 <a href="<?php echo site_url('admin/payment/change_status/'.$payment_engine['id']); ?>" class="btn btn-xs btn-danger">Disable</a>
                 <?php
               }else{
                 ?>
                 <a href="<?php echo site_url('admin/payment/change_status/'.$payment_engine['id']); ?>" class="btn btn-xs btn-success">Enable</a>
                 <?php
               }
                ?>
               <a href="<?php echo site_url('admin/payment/settings/'.$payment_engine['id']); ?>" class="btn btn-info btn-xs">Settings</a>
             </td>
            </tr>
            <?php
          }
           ?>
        </tbody>
      </table>
      <br/><br/>
       <div class="row">
         <div class="col-md-10">
           <h3>Payment Durations</h3>
           <p>You can offer discounts to your customer to encourage them to buy a longer lasting subscription.<br/>
            </p>
         </div>
         <div class="col-md-2">
            <a data-toggle="modal" data-target="#newPaymentDuration" class="btn btn-success pull-right">Create new</a>
         </div>
       </div>
      <br/>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <th class="col-md-6">
              Duration
            </th>
            <th class="col-md-2">
              Discount
            </th>
            <th class="col-md-2">
              Status
            </th>
            <th class="col-md-2">
              Action
            </th>
          </thead>
          <tbody>
            <?php
            foreach($payment_durations as $duration){
              ?>
              <tr>
                <td>
                  <?php echo $duration['months']; ?> Months
                </td>
                <td>
                  <p class="label label-success">
                    <?php echo $duration['discount']; ?> %
                  </p>
                </td>
                <td>
                  <?php
                  if($duration['enabled'] == 1){
                    ?>
                    <a class="btn btn-xs btn-success disabled">
                      Enabled
                    </a>
                    <?php
                  }else{
                    ?>
                    <a class="btn btn-xs btn-danger disabled">
                      Disabled
                    </a>
                    <?php
                  }
                   ?>
                </td>
                <td>
                  <?php
                  if($duration['enabled'] == 1){
                    ?>
                    <a class="btn btn-xs btn-danger" href="<?php echo site_url('admin/payment/paymentTermStatus/'.$duration['id']); ?> ">Disable</a>
                    <?php
                  }else{
                    ?>
                    <a class="btn btn-xs btn-success" href="<?php echo site_url('admin/payment/paymentTermStatus/'.$duration['id']); ?> ">Enable</a>
                    <?php
                  }
                   ?>
                   <a class="btn btn-info btn-xs duration_settings" id="<?php echo $duration['id']; ?>">Settings</a>
                </td>
              </tr>
              <?php
            }
             ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$('.duration_settings').click(function(e){
  e.preventDefault();
  $.get('<?php echo site_url('admin/payment/termInfo/'); ?>' + $(this).attr('id'), function(data){
    result = JSON.parse(data);
    $('#duration').val(result['months']);
    $('#discount').val(result['discount']);
    $('#duration_id').val(result['id']);
  });
  $('#paymentDuration').modal('show');
});
</script>

<div class="modal fade" tabindex="-1" role="dialog" id="paymentDuration">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit payment duration</h4>
      </div>
      <div class="modal-body">
          <?php echo form_open('admin/payment/updatePaymentTerm'); ?>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Duration (months)</label>
                <input name="duration" id="duration" type="number" min="1" max="12" step="1" class="form-control"/>
              </div>
            </div>
            <div class="col-md-4 col-md-offset-4">
              <div class="form-group">
                <label>Discount (%)</label>
                <input name="discount" id="discount" type="number" min="0" max="100" step="0.01" class="form-control"/>
              </div>
              <input type="text" name="duration_id" id="duration_id" style="display: none;" hidden/>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <br/>
                <input type="checkbox" name="delete_duration" value="1"/> <strong style="color: red;">Delete payment duration</strong>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save" style="margin-top: -5px"/>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="newPaymentDuration">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create payment duration</h4>
      </div>
      <div class="modal-body">
          <?php echo form_open('admin/payment/createPaymentTerm'); ?>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Duration (months)</label>
                <input name="duration" id="duration" type="number" min="1" max="12" step="1" class="form-control"/>
              </div>
            </div>
            <div class="col-md-4 col-md-offset-4">
              <div class="form-group">
                <label>Discount (%)</label>
                <input name="discount" id="discount" type="number" min="0" max="100" step="0.01" class="form-control"/>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save" style="margin-top: -5px"/>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
