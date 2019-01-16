<body class="signup-page">
    <div class="signup-box">
        <div class="logo">
            <a href="javascript:void(0);">File<b>BEAR</b></a>
            <small>online revision system</small>
        </div>
        <div class="card">
            <div class="body">
              <?php echo form_open('/register'); ?>
                    <div class="msg">Register a New Account</div>
                    <div class="row">
                      <div class="col-md-12">

                        <?php
                        if($this->session->has_userdata('successMessage')){
                          foreach($this->session->userdata('successMessage') as $message){
                            ?>
                            <div class="alert alert-success">
                                <p class="align-center"><?php echo $message; ?></p>
                            </div>
                            <?php
                          }
                          $this->session->unset_userdata('successMessage');
                        }
                         ?>

                         <?php
                         if($this->session->has_userdata('errorMessage')){
                           foreach($this->session->userdata('errorMessage') as $message){
                             ?>
                             <div class="alert alert-danger align-center">
                                 <p class="align-center"><?php echo $message; ?></p>
                             </div>
                             <?php
                           }
                           $this->session->unset_userdata('errorMessage');
                         }
                          ?>
                        </div>
                      </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="input-group" style="margin-bottom: 0">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="firstname" placeholder="Firstname" required autofocus>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group" style="margin-bottom: 0">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="lastname" placeholder="Lastname" required autofocus>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
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
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password_repeat" placeholder="Repeat Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN UP</button>
                        </div>
                    </div>
                    <div class="m-b--5 align-center">
                            <a href="<?php echo site_url('/login'); ?>">Already have an account?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
