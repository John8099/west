<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include("conn.php");
date_default_timezone_set("Asia/Manila");
$dateNow = date("Y-m-d h:i:s");

$SERVER_NAME = "http://$_SERVER[SERVER_NAME]";
$ADMIN_ROLES = array(
  "instructor",
  "coordinator",
  "panel",
  "adviser",
);

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
      case "sendToInstructor":
        sendToInstructor();
        break;
      case "checkAssignedInstructor":
        checkAssignedInstructor();
        break;
      case "getCurrentInstructorWithOther":
        getCurrentInstructorWithOther();
        break;
      case "updateInstructor":
        updateInstructor();
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
      case "getAllPanel":
        getAllPanel();
        break;
      case "updateGroupAdmin":
        updateGroupAdmin();
        break;
      case "updateSystem":
        updateSystem();
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

function updateSystem()
{
  global $conn, $_POST, $_FILES, $SERVER_NAME;

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
      $system_logo_url = "$SERVER_NAME/west/public/$uploadFile";
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
      $cover_url = "$SERVER_NAME/west/public/$uploadFile";
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

function updateGroupAdmin()
{
  global $conn, $_POST;

  $group_id = $_POST["group_id"];
  $admin_id = $_POST["admin_id"];

  $action = $_POST['action'];

  $query = mysqli_query(
    $conn,
    "UPDATE thesis_groups SET " . ($action == "updateGroupPanel" ? "panel_id" : "instructor_id") . "='$admin_id' WHERE id=$group_id"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Group updated successfully.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function addAdmin()
{
  global $conn, $_POST, $_FILES, $dateNow, $SERVER_NAME;

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
        $img_url = "$SERVER_NAME/west/media/avatar/$uploadFile";

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

function updateInstructor()
{
  global $conn, $_SESSION, $_POST;

  $instructorId = $_POST["instructorId"];
  $currentUser = get_user_by_username($_SESSION["username"]);

  $query = mysqli_query(
    $conn,
    "UPDATE thesis_groups SET instructor_id='$instructorId' WHERE group_leader_id='$currentUser->id' and group_number='$currentUser->group_number'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Instructor updated successfully.";
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

    $response["currentInstructor"] = ucwords("$currentInstructor->first_name $currentInstructor->last_name");
    $response["success"] = true;
  } else {
    $response["success"] = false;
    $response["message"] = "Error updating instructor.<br>Please try again later.";
  }

  returnResponse($response);
}

function sendToInstructor()
{
  global $conn, $_POST, $_SESSION;

  $currentUser = get_user_by_username($_SESSION["username"]);

  $group_mate_id = getGroupMateIds($currentUser->group_number, $currentUser->id);
  $instructorId = $_POST['instructorId'];

  $query = mysqli_query(
    $conn,
    "INSERT INTO thesis_groups(group_number, group_leader_id, group_member_ids, instructor_id) VALUES('$currentUser->group_number', '$currentUser->id', '$group_mate_id', '$instructorId')"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Group list submitted to instructor";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }
  returnResponse($response);
}

function getMemberData($group_number, $leader_id)
{
  global $conn;
  $arr = array();

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE group_number='$group_number' and leader_id='$leader_id'"
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

function checkAssignedInstructor()
{
  global $_SESSION;
  $currentUser = get_user_by_username($_SESSION['username']);
  $instructor = getInstructorData($currentUser);

  $isAlreadySubmitted = isGroupListSubmitted($currentUser);

  if ($isAlreadySubmitted) {
    $response["isAlreadySubmitted"] = true;
  } else {
    $response["isAlreadySubmitted"] = false;
  }

  if ($instructor) {
    $response["hasInstructor"] = true;
  } else {
    $response["hasInstructor"] = false;
  }

  returnResponse($response);
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

  $response["panels"] = array();

  while ($row = mysqli_fetch_object($query)) {
    array_push($response["panels"], $row);
  }

  returnResponse($response);
}

function deleteUser()
{
  global $conn, $_POST, $SERVER_NAME;

  $user = get_user_by_id($_POST['id']);
  $path = str_replace("$SERVER_NAME/west/", "../", $user->avatar);

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
    unlink($path);
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
  global $conn, $_POST, $_FILES, $dateNow, $SERVER_NAME;

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
        $img_url = "$SERVER_NAME/west/media/avatar/$uploadFile";

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
  global $_POST, $_FILES, $SERVER_NAME;

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
            $img_url = "$SERVER_NAME/west/media/avatar/$uploadFile";

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
          $img_url = "$SERVER_NAME/west/media/avatar/$uploadFile";

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
