<?php

//Check if the form got submitted
$success = null;
if(isset($_POST['host']) && isset($_POST['user']) && isset($_POST['password']) && isset($_POST['database'])){
  $hostname = $_POST['host'];
  $username = $_POST['user'];
  $password = $_POST['password'];
  $database = $_POST['database'];

  //Check that we can verify a connection
  try{
    $db = new PDO('mysql:host='.$hostname.';dbname='.$database, $username, $password);
    $db = null;

    //Save the data into the config file config/database.php
    $content = '<?php

    $active_group = "default";
        $query_builder = TRUE;

        $db["default"] = array(
        	"dsn"	=> "",
        	"hostname" => "'.$hostname.'",
        	"username" => "'.$username.'",
        	"password" => "'.$password.'",
        	"database" => "'.$database.'",
        	"dbdriver" => "mysqli",
        	"dbprefix" => "",
        	"pconnect" => FALSE,
        	"db_debug" => (ENVIRONMENT !== "production"),
        	"cache_on" => FALSE,
        	"cachedir" => "",
        	"char_set" => "utf8",
        	"dbcollat" => "utf8_general_ci",
        	"swap_pre" => "",
        	"encrypt" => FALSE,
        	"compress" => FALSE,
        	"stricton" => FALSE,
        	"failover" => array(),
        	"save_queries" => TRUE
        );
';

    file_put_contents('../application/config/database.php', $content);
    $success = true;
  }catch(Exception $e){
    $error_msg[] = 'Could\'t establish a connection to the database!';
    $success = false;
  }
}

require_once('templates/header.php');
require_once('templates/step3.php');
 ?>
