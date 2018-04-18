<?php

// show database errors during development
function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' . $exception->getMessage()) . '</strong></p>';
}

// execute an SQL query and return the results
function exec_sql_query($db, $sql, $params = array()) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  }
  catch (PDOException $exception) {
    handle_db_error($exception);
  }
  return NULL;
}

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      }
      catch (PDOException $exception) {
        handle_db_error($exception);
      }
    }
  }
  else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

$db = open_or_init_sqlite_db('gallery.sqlite', "init/init.sql");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

const GALLERY_UPLOADS_PATH = "uploads/images/";

// functions checkLogin(), setLogin(), and setLogout() were adapted from Lab #8
function checkLogin() {
  global $db;
  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];
    $sqlCheck = "SELECT * FROM users WHERE session = :session";
    $paramsCheck = array(
      ':session' => $session
    );
    $recordsCheck = exec_sql_query($db, $sqlCheck, $paramsCheck)->fetchAll();
    if ($recordsCheck) {
      $user = $recordsCheck[0];
      return $user['username'];
    }
  }
  return NULL;
}

function setLogin($username, $password) {
  global $db;
  if ($username && $password) {
    $sqlLogin = "SELECT * FROM users WHERE username = :username;";
    $paramsLogin = array(
      ':username' => $username
    );
    $recordsLogin = exec_sql_query($db, $sqlLogin, $paramsLogin)->fetchAll();
    if ($recordsLogin) {
      $user = $recordsLogin[0];
      // check password against hash in database
      if (password_verify($password, $user['password'])) { // password_verify is a built-in PHP function
        // generate session
        // warning! this is not a secure method for generating session IDs!
        // TODO: secure session
        $session = uniqid();
        $sqlLogin = "UPDATE users SET session = :session WHERE id = :user_id;";
        $paramsLogin = array(
          ':user_id' => $user['id'],
          ':session' => $session
        );
        $resultLogin = exec_sql_query($db, $sqlLogin, $paramsLogin);
        // if successfully logged in
        if ($resultLogin) {
          // send the following back to user
          setcookie("session", $session, time()+3600); // the session will expire after an hour
          echo "<span class=''>Successfully logged in as " . $username . ".</span>";
          return $username;
        }
        else {
          echo "<span class='displaySessionMessage'>Log in failed.</span>";
        }
      }
      else {
        echo "<span class='displaySessionMessage'>Invalid username or password.</span>";
      }
    }
    else {
      echo "<span class='displaySessionMessage'>Invalid username or password.</span>";
    }
  }
  else {
    echo "<span class='displaySessionMessage'>No username or password given.</span>";
  }
  return FALSE;
}

function setLogout() {
  global $currentUser;
  global $db;
  if ($currentUser) {
    $sqlLogout = "UPDATE users SET session = :session WHERE username = :username;";
    $paramsLogout = array(
      ':username' => $currentUser,
      ':session' => NULL
    );
    if (!exec_sql_query($db, $sqlLogout, $paramsLogout)) {
      echo "<span class='displaySessionMessage'>Log out failed.</span>";
    }
  }
  // remove the session from the cookie and force the session to expire
  setcookie("session", "", time()-3600);
  $currentUser = NULL;
}

// check if we should login the user
if (isset($_POST['login'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $username = trim($username);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $currentUser = setLogin($username, $password);
}
else {
  // check if logged in
  $currentUser = checkLogin();
}

// check if we should log out the user
if (isset($_POST['logout'])) {
  setLogout();
  if (!$currentUser) {
    echo "<span class='displaySessionMessage'>Successfully logged out.</span>";
  }
  else {
    echo "<span class='displaySessionMessage'>Log out failed.</span>";
  }
}

if ($currentUser) {
  echo "<span class='displayUser'>Logged in as $currentUser.</span>";
}
else {
  echo "<span class='displayUser'><br></span>";
}

function addTag($submitTags, $imageID) {
  global $db;
  $arrayTags = array_map('trim', explode(',', $submitTags)); // create an array with the new tags as separated by commas
  // $countCombo = 0;
  foreach ($arrayTags as $tag) {
    $sqlCheckTag = "SELECT tag FROM tags WHERE tag='$tag'";
    $resultsCheckTag = exec_sql_query($db, $sqlCheckTag)->fetchAll();
    if (empty($resultsCheckTag)) { // if tag does not already exist
      $sqlNewTag = "INSERT INTO tags (tag) VALUES (:tag)";
      $paramsNewTag = array(
        "tag" => $tag,
      );
      $resultNewTag = exec_sql_query($db, $sqlNewTag, $paramsNewTag); // add new tag into tags table
      $singleTagID = $db->lastInsertId("id");
      $sqlNewCombo = "INSERT INTO gallery (image_id, tag_id) VALUES ($imageID, $singleTagID)";
      $resultNewCombo = exec_sql_query($db, $sqlNewCombo); // add new image and tag combination into gallery table
      // $countCombo += 1;
    }
    else {
      $sqlTagID = "SELECT id FROM tags where tag='$tag'";
      $resultsTagID = exec_sql_query($db, $sqlTagID)->fetchAll();
      $singleTagID = $resultsTagID[0]['id'];
      // query for checking if image and tag combination already exists
      $sqlCheckCombo = "SELECT * FROM gallery WHERE tag_id=$singleTagID AND image_id=$imageID";
      $recordsCheckCombo = exec_sql_query($db, $sqlCheckCombo)->fetchAll();
      if (empty($recordsCheckCombo)) { // if image and tag combination does not already exist
        $sqlNewCombo = "INSERT INTO gallery (image_id, tag_id) VALUES ($imageID, $singleTagID)";
        $resultNewCombo = exec_sql_query($db, $sqlNewCombo); // add new image and tag combination into gallery table
        // $countCombo += 1;
      }
    }
  }
}

?>
