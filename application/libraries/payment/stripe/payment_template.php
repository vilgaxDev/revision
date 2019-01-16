<div class='row'>
        <div class='col-md-4'></div>
        <div class='col-md-4'>
          <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
          <form accept-charset="UTF-8" action="<?php echo site_url('premium/step3'); ?>" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="<?php echo $this->settings['public_key']; ?>" id="payment-form" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓" /></div>

              <div class='form-group form-float required'>
                <div class="form-line">
                  <label class="form-label">Card Number</label>
                  <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-4">
                  <div class='form-group form-float required'>
                    <div class='form-line cvc required'>
                      <label class="form-label">CVC</label>
                      <input autocomplete='off' class='form-control card-cvc' size='4' type='text'>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class='form-group form-float required'>
                    <div class='form-line cvc required'>
                      <label class="form-label">MM</label>
                      <input class='form-control card-expiry-month' size='2' type='text'>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class='form-group form-float required'>
                    <div class='form-line cvc required'>
                      <label class="form-label">YYYY</label>
                      <input class='form-control card-expiry-year' size='4' type='text'>
                    </div>
                  </div>
                </div>
              </div>

            <div class='form-row'>
              <div class='col-md-12'>
                <div class='form-control total btn btn-info'>
                  Total:
                  <span class='amount'><?php echo round($this->amount,2).' '.$this->currency; ?></span>
                </div>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-md-12 form-group'>
                <button class='form-control btn btn-primary submit-button' type='submit'>Pay »</button>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-md-12 error form-group hide'>
                <div class='alert-danger alert'>
                  Please correct the errors and try again.
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class='col-md-4'></div>
    </div>

    <style>
    .submit-button {
  margin-top: 10px;
}
</style>
<script src="<?php echo base_url();?>public/new/plugins/jquery/jquery.min.js"></script>
<script>
$(function() {
  $('form.require-validation').bind('submit', function(e) {
    var $form         = $(e.target).closest('form'),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;

    $errorMessage.addClass('hide');
    $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault(); // cancel on first error
      }
    });
  });
});

$(function() {
  var $form = $("#payment-form");

  $form.on('submit', function(e) {
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
      Stripe.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);
    }
  });

  function stripeResponseHandler(status, response) {
    if (response.error) {
      $('.error')
        .removeClass('hide')
        .find('.alert')
        .text(response.error.message);
    } else {
      // token contains id, last4, and card type
      var token = response['id'];
      // insert the token into the form so it gets submitted to the server
      $form.find('input[type=text]').empty();
      $form.append("<input type='hidden' name='reservation[stripe_token]' value='" + token + "'/>");
      $form.append("<input type='hidden' name='transaction_id' value='<?php echo $this->transaction; ?>'/>");
      $form.get(0).submit();
    }
  }
})
</script>
