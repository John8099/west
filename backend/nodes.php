<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include("conn.php");
date_default_timezone_set("Asia/Manila");
$dateNow = date("Y-m-d H:i:s");

$SERVER_NAME = "http://$_SERVER[SERVER_NAME]/west";
$ADMIN_ROLES = array(
  "instructor",
  "coordinator",
  "panel",
  "adviser",
);

$feedbacksDefault = json_encode(
  array(
    "feedback" => array(),
    "isApproved" => "false",
  )
);

/*
Feedback format

array(
  "message" => "",
  "token" => "",
  "isResolved" => false,
  "date" => "",
)
*/

if (isset($_GET['action'])) {
  try {
    switch ($_GET['action']) {
      case "logout":
        logout();
        break;
      case "student_registration":
        student_registration();
        break;
      case "login":
        login();
        break;
      case "updateUser":
        updateUser();
        break;
      case "addGroupMate":
        addGroupMate();
        break;
      case "deleteUser":
        deleteUser();
        break;
      case "getAllInstructor":
        getAllInstructor();
        break;
      case "getAllAdviser":
        getAllAdviser();
        break;
      case "sendToInstructor":
        sendToInstructor();
        break;
      case "getCurrentInstructorWithOther":
        getCurrentInstructorWithOther();
        break;
      case "addAdmin":
        addAdmin();
        break;
      case "editAdmin":
        updateUser();
        break;
      case "updatePassword":
        updatePassword();
        break;
      case "updateGroupPanel":
        updateGroupPanel();
        break;
      case "updateSystem":
        updateSystem();
        break;
      case "saveCategory":
        saveCategory();
        break;
      case "deleteCategory":
        deleteCategory();
        break;
      case "saveSchedule":
        saveSchedule();
        break;
      case "deleteSchedule":
        deleteSchedule();
        break;
      case "sendAdviserInvite":
        sendAdviserInvite();
        break;
      case "cancelAdvisorInvite":
        cancelAdvisorInvite();
        break;
      case "instructorApprovedGroupList":
        instructorApprovedGroupList();
        break;
      case "handleAdviserInvite":
        handleAdviserInvite();
        break;
      case "saveType":
        saveType();
        break;
      case "deleteType":
        deleteType();
        break;
      case "saveDocument":
        saveDocument();
        break;
      case "approvedDocument":
        approvedDocument();
        break;
      case "fileFeedback":
        fileFeedback();
        break;
      case "markFeedbackResolved":
        markFeedbackResolved();
        break;
      case "saveOldDocuments":
        saveOldDocuments();
        break;
      default:
        null;
        break;
    }
  } catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
  }
}

function getPageCount($searchVal = "", $limit)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM documents WHERE " . ($searchVal == "" ? "" : "title LIKE '%$searchVal%' and ") . " publish_status='PUBLISHED'"
  );

  return ceil(mysqli_num_rows($query) / $limit);
}

function saveOldDocuments()
{
  global $conn, $_POST, $_FILES;

  $title = $_POST["title"];
  $type = $_POST["type"];
  $year = $_POST["year"];
  $description = mysqli_escape_string($conn, nl2br($_POST["description"]));
  $banner = $_FILES["banner"];
  $pdf = $_FILES["pdfFile"];

  if (intval($banner["error"]) == 0 && intval($pdf["error"]) == 0) {

    $bannerFile = date("mdY-his") . "_" . basename($banner['name']);
    $bannerDir = "../media/documents/banner/";
    $bannerUrl = "/media/documents/banner/$bannerFile";

    $pdfFile = date("mdY-his") . "_" . basename($pdf['name']);
    $pdfDir = "../media/documents/files/";
    $pdfUrl = "/media/documents/files/$pdfFile";

    if (!is_dir($bannerDir)) {
      mkdir($bannerDir, 0777, true);
    }

    if (!is_dir($pdfDir)) {
      mkdir($pdfDir, 0777, true);
    }

    if (move_uploaded_file($banner['tmp_name'], "$bannerDir/$bannerFile") && move_uploaded_file($pdf['tmp_name'], "$pdfDir/$pdfFile")) {
      $query = mysqli_query(
        $conn,
        "INSERT INTO documents(title, `type_id`, `year`, `description`, img_banner, project_document, publish_status) VALUES('$title', '$type', '$year', '$description', '$bannerUrl', '$pdfUrl', 'PUBLISHED')"
      );

      if ($query) {
        $response["success"] = true;
        $response["message"] = "Document successfully save.";
      } else {
        $response["success"] = false;
        $response["message"] = mysqli_error($conn);
      }
    }
  } else {
    $response["success"] = false;
    $response["message"] = "An error occurred when uploading documents. Please try again later.";
  }

  returnResponse($response);
}

function markFeedbackResolved()
{
  global $conn, $_POST;

  $id = $_POST["id"];
  $token = $_POST["token"];
  $role = $_POST["role"];
  $column = ($role . "_feedback");

  $document = getDocumentById($id);
  $feedbackData = json_decode($document->$column, true);

  $newFeedBack = array(
    "feedback" => array(),
    "isApproved" => $feedbackData["isApproved"],
  );

  foreach ($feedbackData["feedback"] as $feedback) {
    if ($feedback["token"] == $token) {
      $feedback["isResolved"] = "true";
      array_push($newFeedBack["feedback"], $feedback);
    } else {
      array_push($newFeedBack["feedback"], $feedback);
    }
  }

  $query = mysqli_query(
    $conn,
    "UPDATE documents SET $column='" . json_encode($newFeedBack) . "' WHERE id='$id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Mark resolved successfully.";
    markApprovedIsResolvedAll($id, $role);
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function markApprovedIsResolvedAll($document_id, $role)
{
  global $conn;

  $document = getDocumentById($document_id);
  $column = ($role . "_feedback");
  $feedbackData = json_decode($document->$column, true);

  $newFeedBack = array(
    "feedback" => array(),
    "isApproved" => $feedbackData["isApproved"],
  );

  $countIsResolved = 0;

  foreach ($feedbackData["feedback"] as $feedback) {
    if ($feedback["isResolved"] == "true") {
      $countIsResolved++;
    }
    array_push($newFeedBack["feedback"], $feedback);
  }

  if (count($feedbackData["feedback"]) == $countIsResolved) {
    $newFeedBack["isApproved"] = "true";
    mysqli_query(
      $conn,
      "UPDATE documents SET $column='" . json_encode($newFeedBack) . "' WHERE id='$document_id'"
    );
  }
}

function fileFeedback()
{
  global $conn, $_POST, $dateNow;

  $document_id = $_POST["document_id"];
  $role = $_POST["role"];
  $feedbackArr = array(
    "message" => nl2br($_POST["feedback"]),
    "token" => uniqid(),
    "isResolved" => "false",
    "date" => $dateNow,
  );

  $column = ($role . "_feedback");

  $document = getDocumentById($document_id);
  $feedback = $document->$column == null ? array(
    "feedback" => array(),
    "isApproved" => "false",
  ) : json_decode($document->$column, true);

  array_push($feedback["feedback"], $feedbackArr);

  $query = mysqli_query(
    $conn,
    "UPDATE documents SET $column='" . json_encode($feedback) . "' WHERE id='$document_id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Filed feedback successfully.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function approvedDocument()
{
  global $conn, $_POST;

  $documentId = $_POST['id'];
  $role = $_POST["role"];
  $column = ($role . "_feedback");

  $feedback = json_encode(array(
    "feedback" => array(),
    "isApproved" => "true",
  ));

  if (!isDocumentApproved($documentId, $role)) {
    $query = mysqli_query(
      $conn,
      "UPDATE documents SET $column='$feedback' WHERE id='$documentId'"
    );
    if ($query) {
      $response["success"] = true;
      $response["message"] = "Document successfully approved.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Document already approved";
  }

  returnResponse($response);
}

function isDocumentApproved($documentId, $role)
{
  global $conn;

  $column = ($role . "_feedback");
  $query = mysqli_query(
    $conn,
    "SELECT * FROM documents WHERE id='$documentId'"
  );

  if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_object($query);

    if ($data->$column != null) {
      $feedback = json_decode($data->$column);

      if ($feedback->isApproved == "true") {
        return true;
      }
      return false;
    }
    return false;
  }
  return false;
}

function getDocumentsDataWithUsers($userId, $idOf)
{
  global $conn;

  $queryStr = "SELECT 
  tg.group_leader_id,
  tg.group_number,
  tg.instructor_id,
  tg.adviser_id,
  d.* FROM thesis_groups tg 
  INNER JOIN documents d 
  ON tg.group_leader_id = d.leader_id";

  if ($idOf == "adviser") {
    $queryStr .= " WHERE tg.adviser_id='$userId'";
  } else if ($idOf == "instructor") {
    $queryStr .= " WHERE tg.instructor_id='$userId'";
  }

  $data = array();

  $query = mysqli_query($conn, $queryStr);
  if (mysqli_num_rows($query)) {
    while ($row = mysqli_fetch_object($query)) {
      array_push($data, $row);
    }
  }

  return $data;
}

function saveDocument()
{
  global $conn, $_POST, $_FILES, $_SESSION;

  $currentUser = get_user_by_username($_SESSION['username']);

  $title = $_POST["title"];
  $type = $_POST["type"];
  $year = $_POST["year"];
  $description = mysqli_escape_string($conn, nl2br($_POST["description"]));
  $banner = $_FILES["banner"];
  $pdf = $_FILES["pdfFile"];

  // $feedback = mysqli_escape_string($conn, $feedbacksDefault);

  if (intval($banner["error"]) == 0 && intval($pdf["error"]) == 0) {

    $bannerFile = date("mdY-his") . "_" . basename($banner['name']);
    $bannerDir = "../media/documents/banner/";
    $bannerUrl = "/media/documents/banner/$bannerFile";

    $pdfFile = date("mdY-his") . "_" . basename($pdf['name']);
    $pdfDir = "../media/documents/files/";
    $pdfUrl = "/media/documents/files/$pdfFile";

    if (!is_dir($bannerDir)) {
      mkdir($bannerDir, 0777, true);
    }

    if (!is_dir($pdfDir)) {
      mkdir($pdfDir, 0777, true);
    }

    if (move_uploaded_file($banner['tmp_name'], "$bannerDir/$bannerFile") && move_uploaded_file($pdf['tmp_name'], "$pdfDir/$pdfFile")) {
      $query = mysqli_query(
        $conn,
        "INSERT INTO documents(leader_id, title, `type_id`, `year`, `description`, img_banner, project_document, publish_status) VALUES('$currentUser->id', '$title', '$type', '$year', '$description', '$bannerUrl', '$pdfUrl', 'PENDING')"
      );

      if ($query) {
        $response["success"] = true;
        $response["message"] = "Document successfully submitted.";
      } else {
        $response["success"] = false;
        $response["message"] = "An error occurred when uploading documents. Please try again later.";
      }
    }
  } else {
    $response["success"] = false;
    $response["message"] = "An error occurred when uploading documents. Please try again later.";
  }

  returnResponse($response);
}

function getSubmittedDocuments($currentUser)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM documents WHERE leader_id ='$currentUser->id'"
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_fetch_object(
      $query
    );
  }

  return null;
}

function getDocumentById($id)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM documents WHERE id ='$id'"
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_fetch_object(
      $query
    );
  }

  return null;
}

function hasSubmittedDocuments($currentUser)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM documents WHERE leader_id ='$currentUser->id'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  }
  return false;
}

function saveType()
{
  global $conn, $_POST;

  $action = $_POST["action"];
  $name = ucwords($_POST["name"]);
  $id = isset($_POST["id"]) ? $_POST["id"] : null;

  if (!isTypeExist(strtolower($name), $id)) {
    $query = null;

    if ($action == "add") {
      $query = mysqli_query(
        $conn,
        "INSERT INTO types(`name`) VALUES('$name')"
      );
    } else if ($action == "edit" && $id != null) {
      $query = mysqli_query(
        $conn,
        "UPDATE types SET `name`='$name' WHERE id='$id'"
      );
    }
    if ($query) {
      $message = $action == "add" ? "added" : "updated";
      $response["success"] = true;
      $response["message"] = "Type $message successfully.";
    } else {
      $response["success"] = false;
      $response["message"] = "Error while saving type, Please try again later.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Type already exist.";
  }

  returnResponse($response);
}

function deleteType()
{
  global $conn, $_POST;

  $query = mysqli_query(
    $conn,
    "DELETE FROM types WHERE id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Type successfully deleted.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function isTypeExist($name, $id = null)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM types WHERE LOWER(`name`) like '$name' " . ($id != null ? " and id != '$id'" : "")
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  }
  return false;
}

function handleAdviserInvite()
{
  global $conn, $_POST, $_SESSION;

  $currentUser = get_user_by_username($_SESSION['username']);

  $inviteId = $_POST['invite_id'];
  $leaderId = $_POST['leader_id'];
  $action = $_POST['action'];

  $query = mysqli_query(
    $conn,
    "UPDATE invite SET `status`='" . ($action == "approve" ? "APPROVED" : "DECLINED") . "' WHERE id='$inviteId'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Invitation successfully " . $action . "d.";
    if ($action == "approve") {
      mysqli_query(
        $conn,
        "UPDATE thesis_groups SET adviser_id='$currentUser->id' WHERE	group_leader_id='$leaderId'"
      );
    }
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function instructorApprovedGroupList()
{
  global $conn, $_POST;
  $group_id = $_POST["group_id"];

  $query = mysqli_query(
    $conn,
    "UPDATE thesis_groups SET status = '1' WHERE id ='$group_id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Group list successfully approved.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function cancelAdvisorInvite()
{
  global $conn, $_SESSION;
  $user = get_user_by_username($_SESSION['username']);

  $query = mysqli_query(
    $conn,
    "DELETE FROM invite WHERE leader_id='$user->id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Invitation successfully cancelled.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function sendAdviserInvite()
{
  global $conn, $_POST, $_SESSION;
  $user = get_user_by_username($_SESSION['username']);
  $adviserInviteData = adviserInviteData($_POST['adviserId'], $user->id);

  if ($adviserInviteData == null) {
    $query = mysqli_query(
      $conn,
      "INSERT INTO invite(adviser_id, leader_id, `status`, proposed_title) VALUES('$_POST[adviserId]', '$user->id', 'PENDING', '$_POST[title]')"
    );

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Invitation successfully submitted.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    if ($adviserInviteData->status == "PENDING") {
      $response["success"] = false;
      $response["message"] = "You already have a pending invite.";
    } else if ($adviserInviteData->status == "DECLINED") {
      $response["success"] = false;
      $response["message"] = "Your already declined by this adviser.";
    } else {
      $response["success"] = false;
      $response["message"] = "An error occurred while inviting this adviser.";
    }
  }

  returnResponse($response);
}

function adviserInviteData($adviserId, $leaderId)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM invite WHERE adviser_id='$adviserId' and leader_id='$leaderId'"
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_fetch_object($query);
  } else {
    return null;
  }
}

function deleteSchedule()
{
  global $conn, $_POST;

  $query = mysqli_query(
    $conn,
    "DELETE FROM schedule_list WHERE id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Schedule successfully deleted.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function saveSchedule()
{
  global $conn, $_POST, $_SESSION;

  $id = isset($_POST["id"]) ? $_POST["id"] : null;
  $category_id = $_POST["category_id"];
  $title = $_POST["title"];
  $description = $_POST["description"];
  $schedule_from = $_POST["schedule_from"];
  $schedule_to = $_POST["schedule_to"] != "" ? $_POST["schedule_to"] : null;

  $user = get_user_by_username($_SESSION['username']);

  if (!checkIsHasSchedule($schedule_from, $id)) {
    $query = null;
    if ($id) {
      $query = mysqli_query(
        $conn,
        "UPDATE schedule_list SET " . ($schedule_to != null ? "schedule_to='$schedule_to', is_whole=0, " : "schedule_to='NULL', is_whole=1, ") . " category_id='$category_id', title='$title', description='$description', schedule_from='$schedule_from' WHERE id = '$id'"
      );
    } else {
      $user = get_user_by_username($_SESSION['username']);
      $query = mysqli_query(
        $conn,
        "INSERT INTO schedule_list(
          " . ($schedule_to != null ? "schedule_to, " : "") . "
          is_whole,
          `user_id`, 
          category_id, 
          title, 
          `description`, 
          schedule_from
        ) VALUES(
          " . ($schedule_to != null ? "'$schedule_to', " : "") . "
          " . ($schedule_to == null ? "'1'," : "'0',") . "
          '$user->id', 
          '$category_id', 
          '$title', 
          '$description', 
          '$schedule_from'
        )"
      );
    }

    if ($query) {
      $message = $id == null ? "added" : "updated";
      $response["success"] = true;
      $response["message"] = "Schedule successfully $message";
    } else {
      $response["success"] = false;
      $response["message"] = "Error while saving schedule, Please try again later.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Date conflict to the other schedules.";
  }

  returnResponse($response);
}

function checkIsHasSchedule($schedule_from, $id = null)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM schedule_list " . ($id != null ? "WHERE id !='$id'" : "")
  );

  while ($schedule = mysqli_fetch_object($query)) {
    if ($schedule->is_whole == 1) {
      $start = date("m-d-Y", strtotime($schedule->schedule_from));
      $scheduleFrom = date("m-d-Y", strtotime($schedule_from));

      if ($start == $scheduleFrom) {
        return true;
        break;
      }
    } else {
      $scheduleFrom = strtotime($schedule_from);
      $start = strtotime($schedule->schedule_from);
      $end = strtotime($schedule->schedule_to);

      if (($scheduleFrom >= $start) && ($scheduleFrom <= $end)) {
        return true;
        break;
      }
    }
  }

  return false;
}

function getCategoryById($id)
{
  global $conn;

  return mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT * FROM category_list WHERE id = '$id'"
    )
  );
}

function getAllSchedules()
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM schedule_list"
  );

  $array = array();

  while ($schedule = mysqli_fetch_object($query)) {
    array_push($array, $schedule);
  }

  if (count($array) > 0) {
    return $array;
  }
  return null;
}

function deleteCategory()
{
  global $conn, $_POST;

  $query = mysqli_query(
    $conn,
    "DELETE FROM category_list WHERE id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Category successfully deleted.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function saveCategory()
{
  global $conn, $_POST;

  $action = $_POST["action"];
  $name = ucwords($_POST["name"]);
  $id = isset($_POST["id"]) ? $_POST["id"] : null;

  if (!isCategoryExist(strtolower($name), $id)) {
    $query = null;

    if ($action == "add") {
      $query = mysqli_query(
        $conn,
        "INSERT INTO category_list(`name`) VALUES('$name')"
      );
    } else if ($action == "edit" && $id != null) {
      $query = mysqli_query(
        $conn,
        "UPDATE category_list SET `name`='$name' WHERE id='$id'"
      );
    }
    if ($query) {
      $message = $action == "add" ? "added" : "updated";
      $response["success"] = true;
      $response["message"] = "Category $message successfully.";
    } else {
      $response["success"] = false;
      $response["message"] = "Error while saving category, Please try again later.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Category already exist.";
  }
  returnResponse($response);
}

function isCategoryExist($name, $id = null)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM category_list WHERE LOWER(`name`) like '$name' " . ($id != null ? " and id!='$id'" : "")
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  }
  return false;
}

function updateSystem()
{
  global $conn, $_POST, $_FILES;

  $name = $_POST["name"];
  $content = nl2br($_POST["content"]);
  $contact = $_POST["contact"];
  $system_logo = $_FILES["system_logo"];
  $cover = $_FILES["cover"];

  $queryStr = "UPDATE system_config SET ";

  $system_logo_url = "";
  $cover_url = "";

  if (intval($system_logo["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($system_logo['name']);
    $target_dir = "../public/";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($system_logo['tmp_name'], "$target_dir/$uploadFile")) {
      $system_logo_url = "/public/$uploadFile";
      $queryStr .= "logo='$system_logo_url', ";
    }
  }

  if (intval($cover["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($cover['name']);
    $target_dir = "../public/";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($cover['tmp_name'], "$target_dir/$uploadFile")) {
      $cover_url = "/public/$uploadFile";
      $queryStr .= "cover='$cover_url', ";
    }
  }

  $queryStr .= "system_name = '$name', home_content='$content', contact='$contact'";
  $query = mysqli_query($conn, $queryStr);

  if ($query) {
    $response["success"] = true;
    $response["message"] = "System updated successfully";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function updateGroupPanel()
{
  global $conn, $_POST;

  $group_id = $_POST["groupId"];
  $panel_ids = json_encode($_POST["panel_ids"]);

  $query = mysqli_query(
    $conn,
    "UPDATE thesis_groups SET panel_ids='$panel_ids' WHERE id=$group_id"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Assigned panels successfully.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function addAdmin()
{
  global $conn, $_POST, $_FILES, $dateNow;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];
  $avatar = $_FILES["avatar"];
  $password = password_hash($email, PASSWORD_ARGON2I);

  $role = $_POST["role"];

  $username = generateUsername($fname, $lname);

  if (!isEmailAlreadyUse($email)) {
    $query = null;
    if (intval($avatar["error"]) == 0) {
      $uploadFile = date("mdY-his") . "_" . basename($avatar['name']);
      $target_dir = "../media/avatar";

      if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
      }

      if (move_uploaded_file($avatar['tmp_name'], "$target_dir/$uploadFile")) {
        $img_url = "/media/avatar/$uploadFile";

        $query = mysqli_query(
          $conn,
          "INSERT INTO 
          users(first_name, middle_name, last_name, avatar, username, email, `password`, `role`, date_added, is_new)
          VALUES('$fname', '$mname', '$lname', '$img_url', '$username', '$email', '$password', '$role', '$dateNow', TRUE)"
        );
      } else {
        $response["message"] = "Error Uploading file.";
      }
    } else {
      $query = mysqli_query(
        $conn,
        "INSERT INTO 
        users(first_name, middle_name, last_name, username, email, `password`, `role`, date_added, is_new)
        VALUES('$fname', '$mname', '$lname', '$username', '$email', '$password', '$role', '$dateNow', TRUE)"
      );
    }

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Admin added successfully<br>Would you like to add another?";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already use by other user.";
  }

  returnResponse($response);
}

function sendToInstructor()
{
  global $conn, $_POST, $_SESSION;

  $currentUser = get_user_by_username($_SESSION["username"]);

  $isGroupListSubmitted = isGroupListSubmitted($currentUser);
  $instructorId = $_POST['instructorId'];

  if ($isGroupListSubmitted) {
    $query = mysqli_query(
      $conn,
      "UPDATE thesis_groups SET instructor_id='$instructorId' WHERE group_leader_id='$currentUser->id' and group_number='$currentUser->group_number'"
    );
  } else {
    $group_mate_id = getGroupMateIds($currentUser->group_number, $currentUser->id);
    $query = mysqli_query(
      $conn,
      "INSERT INTO thesis_groups(group_number, group_leader_id, group_member_ids, instructor_id) VALUES('$currentUser->group_number', '$currentUser->id', '$group_mate_id', '$instructorId')"
    );
  }

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Group list submitted to instructor";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function getCurrentInstructorWithOther()
{
  global $conn, $_SESSION;
  $currentUser = get_user_by_username($_SESSION["username"]);

  $query = mysqli_query(
    $conn,
    "SELECT * FROM thesis_groups WHERE group_leader_id='$currentUser->id' and group_number='$currentUser->group_number'"
  );

  if (mysqli_num_rows($query) > 0) {
    $thesisGroupData = mysqli_fetch_object($query);
    $currentInstructor = get_user_by_id($thesisGroupData->instructor_id);

    $response["otherInstructors"] = array();

    $otherInstructorQuery = mysqli_query(
      $conn,
      "SELECT id, first_name, last_name, middle_name FROM users WHERE `role`='instructor' and id != '$currentInstructor->id'"
    );

    while ($row = mysqli_fetch_object($otherInstructorQuery)) {
      array_push($response["otherInstructors"], $row);
    }

    $response["currentInstructor"] = ucwords("$currentInstructor->first_name " . $currentInstructor->middle_name[0] . ". $currentInstructor->last_name");
    $response["success"] = true;
  } else {
    $response["success"] = false;
    $response["message"] = "Error updating instructor.<br>Please try again later.";
  }

  returnResponse($response);
}

function getMemberData($group_number = null, $leader_id)
{
  global $conn;
  $arr = array();

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE " . ($group_number == null ? "" : "group_number='$group_number' and ") . "leader_id='$leader_id'"
  );

  while ($row = mysqli_fetch_object($query)) {
    array_push($arr, $row);
  }

  return json_encode($arr);
}
function getGroupMateIds($group_number, $leader_id)
{
  global $conn;

  $arr = array();

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE group_number='$group_number' and leader_id='$leader_id'"
  );

  while ($row = mysqli_fetch_object($query)) {
    array_push($arr, $row->id);
  }

  return json_encode($arr);
}

function getInstructorData($currentUser)
{
  global $conn;
  $query = mysqli_query(
    $conn,
    "SELECT * FROM thesis_groups WHERE group_number='$currentUser->group_number' and group_leader_id='$currentUser->id'"
  );

  if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_object($query);
    if ($data->instructor_id) {
      return get_user_by_id($data->instructor_id);
    } else {
      return null;
    }
  } else {
    return null;
  }
}

function isGroupListSubmitted($currentUser)
{
  global $conn;
  $query = mysqli_query(
    $conn,
    "SELECT * FROM thesis_groups WHERE group_number='$currentUser->group_number' and group_leader_id='$currentUser->id'"
  );

  if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_object($query)) {
      if ($row->group_leader_id == $currentUser->id) {
        return true;
        break;
      }
    }
  } else {
    return false;
  }
}

function getAllAdviser()
{
  global $conn, $_GET;
  $declineAdviserId = isset($_GET["declineAdviserId"]) ? $_GET["declineAdviserId"] : null;

  $query = mysqli_query(
    $conn,
    "SELECT id, first_name, last_name, middle_name FROM users WHERE `role`='adviser' " . ($declineAdviserId != null ? " and id != '$declineAdviserId'" : "") . ""
  );

  $response["adviser"] = array();

  while ($row = mysqli_fetch_object($query)) {
    array_push($response["adviser"], $row);
  }

  returnResponse($response);
}

function getAllInstructor()
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT id, first_name, last_name, middle_name FROM users WHERE `role`='instructor'"
  );

  $response["instructors"] = array();

  while ($row = mysqli_fetch_object($query)) {
    array_push($response["instructors"], $row);
  }

  returnResponse($response);
}

function getAllPanel()
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT id, first_name, last_name, middle_name FROM users WHERE `role`='panel'"
  );

  $panels = array();

  while ($row = mysqli_fetch_object($query)) {
    array_push($panels, $row);
  }

  return $panels;
}

function deleteUser()
{
  global $conn, $_POST;

  $user = get_user_by_id($_POST['id']);

  $query = mysqli_query(
    $conn,
    "DELETE FROM users WHERE id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "User successfully deleted.";
    if ($user->role == "student") {
      updateGroupList();
    }
    if ($user->role == "instructor") {
      removeInstructorToGroupList($user->id);
    }
    unlink("..$user->avatar");
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function removeInstructorToGroupList($instructorId)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "UPDATE thesis_groups SET instructor_id=NULL WHERE instructor_id='$instructorId'"
  );

  return $query;
}

function updateGroupList()
{
  global $conn;
  $currentUser = get_user_by_username($_SESSION['username']);

  if (!isGroupListSubmitted($currentUser)) {
    $group_mate_id = getGroupMateIds($currentUser->group_number, $currentUser->id);
    mysqli_query(
      $conn,
      "UPDATE thesis_groups set group_member_ids = '$group_mate_id' WHERE group_leader_id='$currentUser->id' and group_number='$currentUser->group_number'"
    );
  }
}

function addGroupMate()
{
  global $conn, $_POST, $_FILES, $dateNow;

  $group_number = $_POST["group_number"];

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $roll = $_POST["roll"];
  $email = $_POST["email"];
  $year = $_POST["year"];
  $section = $_POST["section"];
  $avatar = $_FILES["avatar"];

  $role = "student";

  $username = generateUsername($fname, $lname);

  if (!isEmailAlreadyUse($email) && !isStudentRollExist($roll)) {
    $query = null;
    $currentUser = get_user_by_username($_SESSION['username']);
    if (intval($avatar["error"]) == 0) {
      $uploadFile = date("mdY-his") . "_" . basename($avatar['name']);
      $target_dir = "../media/avatar";

      if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
      }

      if (move_uploaded_file($avatar['tmp_name'], "$target_dir/$uploadFile")) {
        $img_url = "/media/avatar/$uploadFile";

        $query = mysqli_query(
          $conn,
          "INSERT INTO 
          users(roll, first_name, middle_name, last_name, group_number, year_and_section, avatar, username, email, `role`, leader_id, date_added)
          VALUES('$roll', '$fname', '$mname', '$lname', '$group_number', '$year-$section', '$img_url', '$username', '$email', '$role', '$currentUser->id', '$dateNow')"
        );
      } else {
        $response["message"] = "Error Uploading file.";
      }
    } else {
      $query = mysqli_query(
        $conn,
        "INSERT INTO 
        users(roll, first_name, middle_name, last_name, group_number, year_and_section, username, email, `role`, leader_id, date_added)
        VALUES('$roll', '$fname', '$mname', '$lname', '$group_number', '$year-$section', '$username', '$email', '$role', '$currentUser->id', '$dateNow')"
      );
    }

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Group mate added successfully<br>Would you like to add another?";
      updateGroupList();
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    if (!isEmailAlreadyUse($email)) {
      $response["success"] = false;
      $response["message"] = "Email already use by other user.";
    } else {
      $response["success"] = false;
      $response["message"] = "Roll already use by other user.";
    }
  }

  returnResponse($response);
}

function updateUser()
{
  global $_POST, $_FILES;

  $userId = $_POST["userId"];
  $email = $_POST["email"];
  $avatar = $_FILES["avatar"];

  $password = isset($_POST["password"]) ? $_POST["password"] : "";
  $cpassword = isset($_POST["cpassword"]) ? $_POST["cpassword"] : "";
  $oldpassword = isset($_POST["oldpassword"]) ? $_POST["oldpassword"] : "";

  if (!isEmailAlreadyUseWithId($email, $userId)) {
    if ($password != "" || $cpassword != "" || $oldpassword != "") {
      $verifyPassword = json_decode(validatePassword($userId, $password, $cpassword, $oldpassword));
      if ($verifyPassword->validate) {
        $passwordHash = $verifyPassword->hash;

        if (intval($avatar["error"]) == 0) {
          $uploadFile = date("mdY-his") . "_" . basename($avatar['name']);
          $target_dir = "../media/avatar";

          if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
          }

          if (move_uploaded_file($avatar['tmp_name'], "$target_dir/$uploadFile")) {
            $img_url = "/media/avatar/$uploadFile";

            updateUserDB($_POST, $img_url, $passwordHash);
            exit();
          } else {
            $response["message"] = "Error Uploading file.";
          }
        } else {
          updateUserDB($_POST, null, $passwordHash);
          exit();
        }
      } else {
        $response["success"] = false;
        $response["message"] = $verifyPassword->message;
      }
    } else {
      if (intval($avatar["error"]) == 0) {
        $uploadFile = date("mdY-his") . "_" . basename($avatar['name']);
        $target_dir = "../media/avatar";

        if (!is_dir($target_dir)) {
          mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($avatar['tmp_name'], "$target_dir/$uploadFile")) {
          $img_url = "/media/avatar/$uploadFile";

          updateUserDB($_POST, $img_url, null);
          exit();
        } else {
          $response["message"] = "Error Uploading file.";
        }
      } else {
        updateUserDB($_POST, null, null);
        exit();
      }
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already use by other user.";
  }

  returnResponse($response);
}

function updateUserDB($post, $img_url = null, $hash)
{
  global $conn;

  $userId = $post["userId"];
  $role = $post["role"];

  $fname = $post["fname"];
  $mname = $post["mname"];
  $lname = $post["lname"];
  $email = $post["email"];

  $roll = isset($post["roll"]) ? $post["roll"] : null;
  $group_number = isset($post["group_number"]) ? $post["group_number"] : null;
  $year = isset($post["year"]) ? $post["year"] : null;
  $section = isset($post["section"]) ? $post["section"] : null;

  $username = generateUsername($fname, $lname);
  $currentUser = get_user_by_id($userId);

  $query = "";
  if ($role == "student") {
    $query = "UPDATE users SET
    " . ($roll == null ? '' : "roll='$roll',") . "
    first_name='$fname',
    middle_name='$mname',
    last_name='$lname',
    " . ($group_number == null ? '' : "group_number='$group_number',") . "
    " . ($year == null && $section == null ? '' : "year_and_section='$year-$section',") . "
    " . ($img_url == null ? '' : "avatar='$img_url', ") . "
    username='$username',
    email='$email'
    " . ($hash == null ? '' : ", password='$hash'") . " WHERE id='$userId'";
  } else {
    $query = "UPDATE users SET
    " . ($roll == null ? '' : "roll='$roll',") . "
    first_name='$fname',
    middle_name='$mname',
    last_name='$lname',
    " . ($img_url == null ? '' : "avatar='$img_url', ") . "
    email='$email',
    username='$username'
    " . ($hash == null ? '' : ", password='$hash'") . "  WHERE id='$userId'";
  };

  $insertQuery = mysqli_query($conn, $query);

  if ($insertQuery) {
    $response["success"] = true;
    $response["message"] = $role != "student" ? "Admin updated successfully." : "User updated successfully.";
    if ($_SESSION["username"] == $currentUser->username) {
      $_SESSION["username"] = $username;
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Error updating user.";
  }

  returnResponse($response);
}

function validatePassword($user_id, $password, $confirm_password, $old_password)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id'"
  );

  $arr = array();

  if (mysqli_num_rows($query) > 0) {
    $user = get_user_by_id($user_id);

    if ($password == $confirm_password && $password != $old_password) {
      if (password_verify($old_password, $user->password)) {
        $arr["validate"] = true;
        $arr["hash"] = password_hash($password, PASSWORD_ARGON2I);
      } else {
        $arr["validate"] = false;
        $arr["message"] = "Password Error";
      }
    } else if ($password == $old_password) {
      $arr["validate"] = false;
      $arr["message"] = "New password and Old password should not be the same.";
    } else {
      $arr["validate"] = false;
      $arr["message"] = "New password and Confirm password not match.";
    }
  } else {
    $arr["validate"] = false;
    $arr["message"] = "Could not find user.";
  }
  return json_encode($arr);
}

function isEmailAlreadyUseWithId($email, $userId)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email' and id != '$userId'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  } else {
    return false;
  }
}

function login()
{
  global $conn, $_POST;

  $email = $_POST["email"];
  $password = $_POST["password"];

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email'"
  );

  if (mysqli_num_rows($query) > 0) {
    $user = get_user_by_email($email);
    if (password_verify($password, $user->password)) {
      $response["success"] = true;
      $response["role"] = $user->role;
      $response["isNew"] = $user->is_new ? true : false;
      $_SESSION["username"] = $user->username;
    } else {
      $response["success"] = false;
      $response["message"] = "Password error";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "User doesn't exist.";
  }

  returnResponse($response);
}

function get_user_by_email($email)
{
  global $conn;
  return mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT * FROM users WHERE email = '$email'"
    )
  );
}

function get_user_by_username($username)
{
  global $conn;
  return mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT * FROM users WHERE username = '$username'"
    )
  );
}

function get_user_by_id($user_id)
{
  global $conn;

  return mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT * FROM users WHERE id = '$user_id'"
    )
  );
}

function student_registration()
{
  global $conn, $_SESSION, $_POST, $dateNow;

  $roll = $_POST["roll"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"] == "" ? null : $_POST["mname"];
  $lname = $_POST["lname"];
  $group_number = $_POST["group_number"];
  $year = $_POST["year"];
  $section = $_POST["section"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_ARGON2I);

  $username = generateUsername($fname, $lname);

  $role = "student";

  if (!isEmailAlreadyUse($email) || !isStudentRollExist($roll)) {
    $query = mysqli_query(
      $conn,
      "INSERT INTO 
      users(roll, first_name, middle_name, last_name, group_number, year_and_section, username, email, `password`, `role`, isLeader, date_added)
      VALUES('$roll', '$fname', " . ($mname ? "'$mname'" : 'NULL') . ", '$lname', '$group_number', '$year-$section', '$username', '$email', '$password', '$role', '1', '$dateNow')"
    );

    if ($query) {
      $response["success"] = true;
      $response["message"] = "User registered successfully";
      $response["role"] = $role;
      $_SESSION["username"] = $username;
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {

    if (!isEmailAlreadyUse($email)) {
      $response["success"] = false;
      $response["message"] = "Email already use by other user.";
    } else {
      $response["success"] = false;
      $response["message"] = "Roll already use by other user.";
    }
  }

  returnResponse($response);
}

function isEmailAlreadyUse($email)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  } else {
    return false;
  }
}

function isStudentRollExist($roll)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE roll='$roll'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  } else {
    return false;
  }
}

function logout()
{
  global $_SESSION;
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  session_destroy();
  header("location: ../");
}

function updatePassword()
{
  global $conn, $_POST;

  $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

  $query = mysqli_query(
    $conn,
    "UPDATE users SET `password`='$password', is_new=FALSE WHERE id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Password updated successfully";
    $currentUser = get_user_by_id($_POST['id']);
    $response["role"] = $currentUser->role;
  } else {
    $response["success"] = false;
    $response["message"] = "Something went wrong while updating password. Please try again later";
  }

  returnResponse($response);
}

function systemInfo()
{
  global $conn;

  return (mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT * FROM system_config"
    )
  )
  );
}

function generateUsername($fname, $lname)
{
  return strtolower("$fname-$lname-") . preg_replace('/[^A-Za-z0-9\-]/', '', base64_encode(random_bytes(9)));
}

function returnResponse($params)
{
  print_r(
    json_encode($params)
  );
}
