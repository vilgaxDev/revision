<?php

//Check if the form got submited
if(isset($_POST['firstname'])){
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password_repeat = $_POST['password_repeat'];
  $register = true;
  $success = false;

  //Check if both passwords match and at least 5 characters
  if(!$password == $password_repeat && !strlen($password) > 4){
    $register = false;
    $error_msg[] = "Password don't match or aren't at least 5 characters long";
  }

  //Check if the user provided a valid email address
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $register = false;
    $error_msg[] = "Please provide a valid email address";
  }

  //Register the user
  if($register = true){
    try{
      $encrypted_password = hash("sha256", $password);
      require_once('../application/config/database.php');
      $dbh = new PDO('mysql:host='.$db["default"]["hostname"].';dbname='.$db["default"]["database"], $db["default"]["username"], $db["default"]["password"]);

      $sql = "INSERT INTO users (firstname, lastname, email, password, group_id, active) VALUES ('$firstname', '$lastname', '$email', '$encrypted_password', 1,1)";
      $dbh->exec($sql);
      $success = true;
    }catch(Exception $e){
      $error_msg[] = "Connection to database failed!";
    }
  }
}

require_once('templates/header.php');
require_once('templates/step6.php');
