<?php

//Check if the form got submitted
if(isset($_POST['page_title'])){
  $title = $_POST['page_title'];

  //Insert the page title in the database
  require_once('../application/config/database.php');
  $dbh = new PDO('mysql:host='.$db["default"]["hostname"].';dbname='.$db["default"]["database"], $db["default"]["username"], $db["default"]["password"]);

  //Check if the row already exists
  $query = $dbh->prepare("SELECT * FROM setting_items WHERE name = 'page_title'");
  $query->execute();

  if($query->rowCount() > 0){
    $dbh->exec("UPDATE setting_items SET content='".$title."' WHERE name = 'page_title'");
    $success = true;
  }else{
    $dbh->exec("INSERT INTO setting_items (name,content) VALUES ('page_title', '".$title."')");
    $success = true;
  }

  //Check if a thumbnail got uploaded
  if(isset($_FILES['thumbnail']['tmp_name'])){
    //Check that we have an png image
    if($_FILES['thumbnail']['type'] == 'image/png'){
      move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../application/uploads/favicon.png');
      $success = true;
    }
  }

}

require_once('templates/header.php');
require_once('templates/step5.php');
 ?>
