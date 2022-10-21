<?php
$self = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

$links = array(

  array(
    "title" => "Home",
    "url" => "$SERVER_NAME/pages/student/index",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Thesis Documents",
    "url" => "$SERVER_NAME/pages/archives",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Profile",
    "url" => "$SERVER_NAME/pages/profile",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Groupings",
    "url" => "$SERVER_NAME/pages/student/my-groupings",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Schedules",
    "url" => "$SERVER_NAME/pages/student/schedule",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Document Status",
    "url" => "$SERVER_NAME/pages/student/document-status",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Messages",
    "url" => "$SERVER_NAME/pages/student/messages",
    "allowedViews" => array("student")
  ),
  array(
    "title" => "Dashboard",
    "url" => "$SERVER_NAME/pages/admin/index",
    "allowedViews" => array("coordinator", "instructor", "panel", "adviser"),
    "config" => array(
      "icon" => "tachometer-alt"
    )
  ),
  array(
    "title" => "Messages",
    "url" => "$SERVER_NAME/pages/admin/messages",
    "allowedViews" => array("coordinator", "instructor", "panel", "adviser"),
    "config" => array(
      "icon" => "paper-plane"
    )
  ),
  array(
    "title" => "Task Category",
    "url" => "$SERVER_NAME/pages/admin/task-category",
    "allowedViews" => array("coordinator", "panel", "adviser"),
    "config" => array(
      "icon" => "th-list"
    )
  ),
  array(
    "title" => "Scheduled task",
    "url" => "$SERVER_NAME/pages/admin/scheduled-task",
    "allowedViews" => array("coordinator", "instructor", "panel", "adviser"),
    "config" => array(
      "icon" => "calendar-week"
    )
  ),
  array(
    "title" => "Thesis type",
    "url" => "$SERVER_NAME/pages/admin/type",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "th-list"
    )
  ),
  array(
    "title" => "Students",
    "url" => "$SERVER_NAME/pages/admin/user-lists",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "users-cog"
    )
  ),
  array(
    "title" => "Groups",
    "url" => "$SERVER_NAME/pages/admin/groups",
    "allowedViews" => array("instructor"),
    "config" => array(
      "icon" => "users-cog"
    )
  ),
  array(
    "title" => "Invites",
    "url" => "$SERVER_NAME/pages/admin/invites",
    "allowedViews" => array("adviser"),
    "config" => array(
      "icon" => "users-cog"
    )
  ),
  array(
    "title" => "Admins",
    "url" => "$SERVER_NAME/pages/admin/admin-lists",
    "allowedViews" => array("coordinator"),
    "config" => array(
      "icon" => "users-cog"
    )
  ),
  array(
    "title" => "Settings",
    "url" => "$SERVER_NAME/pages/admin/settings",
    "allowedViews" => array("coordinator", "panel", "adviser"),
    "config" => array(
      "icon" => "tools"
    )
  ),

);
