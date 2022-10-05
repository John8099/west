<?php
// $listOfFiles = listOfFilesUploadedInDb();

function folderFiles($dir, $arrFilesInDb)
{
  $folder = scandir($dir);

  unset($folder[array_search('.', $folder, true)]);
  unset($folder[array_search('..', $folder, true)]);

  if (count($folder) < 1)
    return;

  foreach ($folder as $ff) {
    if (is_dir($dir . '/' . $ff)) {
      folderFiles($dir . '/' . $ff, $arrFilesInDb);
    }
    if (!in_array($ff, $arrFilesInDb) && !is_dir($dir . '/' . $ff)) {
      unlink(__DIR__ . "/" . $dir . "/" . $ff);
    }
  }
  return "true";
}

function listOfFilesUploadedInDb()
{
  include_once("backend/conn.php");
  $listOfFiles = [];

  $user_q = mysqli_query($conn, "SELECT * FROM users");
  while ($a = mysqli_fetch_object($user_q)) {
    $exploded = explode("/", $a->avatar);
    array_push($listOfFiles, $exploded[count($exploded) - 1]);
  }

  // $reports_q = mysqli_query($conn, "SELECT * FROM reports");
  // while ($b = mysqli_fetch_object($reports_q)) {
  //   $exploded = explode("/", $b->report_file_name);
  //   array_push($listOfFiles, $exploded[count($exploded) - 1]);
  // }
  return $listOfFiles;
}
