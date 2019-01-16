<?php
//Remove the header statement from the index.php file

$index_file = file_get_contents('../index.php');
$content = str_replace('header("Location: ../installer/index.php");exit();', '', $index_file);
file_put_contents('../index.php', $content);

//Success page
require_once('templates/header.php');
require_once('templates/step7.php');
?>
