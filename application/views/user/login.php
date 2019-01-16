<style>
  html{ height: 100%; }
  a:hover{
    text-decoration: none;
  }
  .logo a {
    font-size: 36px;
    display: block;
    width: 100%;
    text-align: center;
    color: #fff;
}
.logo small {
    display: block;
    width: 100%;
    text-align: center;
    color: #fff;
    margin-top: -5px;
}
</style>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<body class="login-page">
    <br />
    <div class="logo">
        <a href="javascript:void(0);">File<b>BEAR</b></a>
        <small>online revision system</small>
    </div>
    <br /><br />
    <div class="card">
        <div class="body">
          <?php echo form_open('/login'); ?>
                <div class="msg">Sign into your account</div>
                <div class="row">
                  <div class="col-md-12">
                    <?php
                    if(!empty($error_message)){
                      foreach($error_message as $message){
                        ?>
                        <p class="alert alert-danger"><?php echo $message; ?></p>
                        <?php
                      }
                    }
                    if(!empty($success_message)){
                      foreach($success_message as $message){
                        ?>
                        <p class="alert alert-success"><?php echo $message; ?></p>
                        <?php
                      }
                    }
                     ?>
                    </div>
                  </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input type="email" class="form-control" name="username" placeholder="Email Address" required autofocus>
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="row">
                  <div class="col-xs-6">

                      <a class="btn btn-block bg-green waves-effect" href="<?php echo site_url('user/resetpw'); ?>">Forgot Password?</a>
                  </div>
                    <div class="col-xs-4 col-xs-offset-2">
                        <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                    </div>
                    <div class="col-xs-12">
                        <a class="btn btn-block bg-blue waves-effect" href="<?php echo site_url('register'); ?>">Register Now!</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.6/typed.min.js"></script>
<script>
$('document').ready(function(){
  var typed = new Typed('.inner_slogan', {
  strings: ["File Hosting.", "File Sharing.", "Self Hosted.", "Mobile Ready.", "PayPal Integrated."],
  typeSpeed: 40,
  backSpeed: 40,
  loop: true
});
})
</script>
