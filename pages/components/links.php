<?php
$self = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

$links = array(

  array(
    "title" => "Home",
    "url" => "$SERVER_NAME/west/pages/student/index",
    "config" => array("student")
  ),
  array(
    "title" => "Thesis Documents",
    "url" => "$SERVER_NAME/west/pages/archives",
    "config" => array("student")
  ),
  array(
    "title" => "Profile",
    "url" => "$SERVER_NAME/west/pages/profile",
    "config" => array("student")
  ),

);
