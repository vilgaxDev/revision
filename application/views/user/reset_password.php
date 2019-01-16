<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">File<b>BEAR</b></a>
            <small>online revision system</small>
        </div>
        <div class="card">
            <div class="body">
    <div class="row">
      <div class="col-md-12">

        <?php
        if(!empty($successMessage)){
          foreach($successMessage as $message){
            ?>
            <div class="alert alert-success">
                <p class="align-center"><?php echo $message; ?></p>
            </div>
            <?php
          }
        }
         ?>

         <?php
         if(!empty($errorMessage)){
           foreach($errorMessage as $message){
             ?>
             <div class="alert alert-danger align-center">
                 <p class="align-center"><?php echo $message; ?></p>
             </div>
             <?php
           }
         }
          ?>
        </div>
       </div>
    <div class="msg">Reset your account password</div>
    <?php echo form_open('/user/resetpw'); ?>
      <div class="input-group">
                              <span class="input-group-addon">
                                  <i class="material-icons">person</i>
                              </span>
                              <div class="form-line">
                                  <input type="email" class="form-control" name="email_address" placeholder="Email Address" required="" autofocus="">
                              </div>
                          </div>
        <button type="submit" class="btn btn-green" style="display: block; margin: auto auto;">Reset Password</button>
    </form>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
