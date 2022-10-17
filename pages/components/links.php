<?php
$self = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

$links = array(

  array(
    "title" => "Home",
    "url" => "$SERVER_NAME/west/pages/student/index",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Thesis Documents",
    "url" => "$SERVER_NAME/west/pages/archives",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Profile",
    "url" => "$SERVER_NAME/west/pages/profile",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "My Groupings",
    "url" => "$SERVER_NAME/west/pages/student/my-groupings",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Submit Documents",
    "url" => "$SERVER_NAME/west/pages/student/submit-documents",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Dashboard",
    "url" => "$SERVER_NAME/west/pages/$user->role/index",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "tachometer-alt"
    )
  ),
  array(
    "title" => "Task Category",
    "url" => "$SERVER_NAME/west/pages/$user->role/task-category",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "th-list"
    )
  ),
  array(
    "title" => "Scheduled task",
    "url" => "$SERVER_NAME/west/pages/$user->role/scheduled-task",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "calendar-week"
    )
  ),
  array(
    "title" => "Students",
    "url" => "$SERVER_NAME/west/pages/$user->role/user-lists",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "users-cog"
    )
  ),
  array(
    "title" => "Admins",
    "url" => "$SERVER_NAME/west/pages/$user->role/admin-lists",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "users-cog"
    )
  ),
  array(
    "title" => "Settings",
    "url" => "$SERVER_NAME/west/pages/settings",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "tools"
    )
  ),

);
