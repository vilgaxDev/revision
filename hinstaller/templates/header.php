<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>FileBear Installation</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
    <style>
    .nav a{
      color: #777;
    }
    .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{
      background-color: #f9a93b;
    }
    </style>
  </head>
  <body>
    <div class="container" style="max-width: 800px">
      <img src="img/logo.png"  />
      <div class="row">
            <div class="col-xs-12">
                <ul class="nav nav-pills nav-justified thumbnail">
                    <li id="step1"><a href="#">
                        <h4 class="list-group-item-heading">Start</h4>
                    </a></li>
                    <li id="step2"><a href="#">
                        <h4 class="list-group-item-heading">Requirements</h4>
                    </a></li>
                    <li id="step3"><a href="#">
                        <h4 class="list-group-item-heading">Database</h4>
                    </a></li>
                    <li id="step4" class=""><a href="#">
                        <h4 class="list-group-item-heading">General</h4>
                    </a></li>
                    <li id="step5" class=""><a href="#">
                        <h4 class="list-group-item-heading">User</h4>
                    </a></li>
                    <li id="step6" class=""><a href="#">
                        <h4 class="list-group-item-heading">Finish</h4>
                    </a></li>
                </ul>
            </div>
    	</div>

      <?php
      if(!empty($error_msg)){
        ?>
        <div class="row">
          <div class="col-xs-12">
            <?php
            foreach($error_msg as $msg){
              ?>
              <p class="alert alert-danger">
                <?php echo $msg; ?>
              </p>
              <?php
            }
             ?>
          </div>
        </div>
        <?php
      }
       ?>
