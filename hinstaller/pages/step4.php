<?php
if(isset($_POST['submit'])){
  try{
    require_once('../application/config/database.php');
    $dbh = new PDO('mysql:host='.$db["default"]["hostname"].';dbname='.$db["default"]["database"], $db["default"]["username"], $db["default"]["password"]);

    //Insert the sql template into the database
    $sql = file_get_contents('structure.sql');
    $dbh->exec($sql);

    $success = true;

  }catch(Exception $e){
    $error_msg[] = 'Operation failed!';
    $success = false;
  }
}

require_once('templates/header.php');
require_once('templates/step4.php');
 ?>
