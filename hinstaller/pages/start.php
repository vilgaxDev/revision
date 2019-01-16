<?php

//Check if the terms were accepted

$tos = $_POST['agree'];
if(isset($_POST['submit'])){
  if(!$tos == 1){
    $error_msg[] = 'Please accept the above message';
  }else{
    header('Location: index.php?step=2');
  }
}

//Display the index page
require_once('templates/header.php');
require_once('templates/start.php');
?>
