<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("conn.php");
date_default_timezone_set("Asia/Manila");
$dateNow = date("Y-m-d h:i:s");

$SERVER_NAME = "http://$_SERVER[SERVER_NAME]";

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
      default:
        null;
        break;
    }
  } catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
  }
}

function updateUser()
{
  global $conn, $_POST, $_SESSION, $_FILES, $SERVER_NAME;

  $userId = $_POST["userId"];
  $email = $_POST["email"];
  $avatar = $_FILES["avatar"];

  $password = $_POST["password"];
  $cpassword = $_POST["cpassword"];
  $oldpassword = $_POST["oldpassword"];

  if (!checkIsEmailExist($email, $userId)) {
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
            $img_url = "$SERVER_NAME/west/media/$uploadFile";

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
  $group_number = isset($post["group_number"]) ? $post["group_number"] : null;
  $year = isset($post["year"]) ? $post["year"] : null;
  $section = isset($post["section"]) ? $post["section"] : null;

  $username = strtolower("$fname-$lname-") . base64_encode(random_bytes(9));

  $query = "";
  if ($role == "student") {
    $query = "UPDATE users SET first_name='$fname', middle_name='$mname', last_name='$lname', group_number='$group_number', year_and_section='$year-$section', " . ($img_url == null ? '' : "avatar='$img_url', ") . "username='$username', email='$email' " . ($hash == null ? '' : ", password='$hash'") . " WHERE id='$userId'";
  } else {
    $query = "UPDATE users SET first_name='$fname', middle_name='$mname', last_name='$lname', avatar='$img_url', " . ($img_url == null ? '' : "avatar='$img_url', ") . " email='$email' " . ($hash == null ? '' : ", password='$hash'") . "  WHERE id='$userId'";
  };

  $insertQuery = mysqli_query($conn, $query);

  if ($insertQuery) {
    $response["success"] = true;
    $_SESSION["username"] = $username;
    $response["message"] = "User updated successfully.";
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

function checkIsEmailExist($email, $userId)
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

  $fname = $_POST["fname"];
  $mname = $_POST["mname"] == "" ? null : $_POST["mname"];
  $lname = $_POST["lname"];
  $group_number = $_POST["group_number"];
  $year = $_POST["year"];
  $section = $_POST["section"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_ARGON2I);

  $username = strtolower("$fname-$lname-") . base64_encode(random_bytes(9));

  $role = "student";

  if (!isEmailAlreadyUse($email)) {
    $query = mysqli_query(
      $conn,
      "INSERT INTO 
      users(first_name, middle_name, last_name, group_number, year_and_section, username, email, `password`, `role`, date_added)
      VALUES('$fname', '$mname', '$lname', '$group_number', '$year-$section', '$username', '$email', '$password', '$role', '$dateNow')"
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
    $response["success"] = false;
    $response["message"] = "Email already use by other user.";
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

function returnResponse($params)
{
  print_r(
    json_encode($params)
  );
}
