<?php
$current_page = $_GET['step'];
$error_msg = array();

//Check if we're on the index page
if($current_page == NULL){
  require_once('pages/start.php');
}else{
  try {
    require_once('pages/step'.$current_page.'.php');
  } catch (Exception $e) {
    echo "Page not found";
  }

}



 ?>
