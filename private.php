<?php
$pageCurrent = "my gallery";
include("includes/init.php");

const MAX_FILE_SIZE = 8000000;

if (isset($_POST["upload"])) {
  $uploadTitle = strtolower(filter_input(INPUT_POST, 'imageTitle', FILTER_SANITIZE_STRING));
  $uploadTags = strtolower(filter_input(INPUT_POST, 'imageTags', FILTER_SANITIZE_STRING));
  $uploadFile = $_FILES["imageFile"];
  if ($uploadFile['error'] == UPLOAD_ERR_OK) {
    $uploadName = strtolower(basename($uploadFile["name"]));
    $uploadExt = strtolower(pathinfo($uploadName, PATHINFO_EXTENSION));
    $userQuery = "SELECT id FROM users WHERE username = '$currentUser'";
    $userResult = exec_sql_query($db, $userQuery)->fetchAll();
    $userID = $userResult[0]['id'];
    $sqlUpload = "INSERT INTO images (user_id, image, title, ext) VALUES (:user_id, :image, :title, :ext)";
    $paramsUpload = array(
      ':user_id' => $userID,
      ':image' => $uploadName,
      ':title' => $uploadTitle,
      ':ext' => $uploadExt
    );
    $resultImage = exec_sql_query($db, $sqlUpload, $paramsUpload);
    if ($resultImage) {
      $imageID = $db->lastInsertId("id");
      if (move_uploaded_file($uploadFile["tmp_name"], GALLERY_UPLOADS_PATH . "$imageID.$uploadExt")) {
        addTag($uploadTags, $imageID);
        echo "<span class='displayUploadMessage'>Successfully uploaded image.</span>";
      }
      else {
        echo "<span class='displayUploadMessage'>Failed to upload image.</span>";
      }
    }
    else {
      echo "<span class='displayUploadMessage'>Failed to upload image.</span>";
    }
  }
  else if ($uploadFile['error'] == UPLOAD_ERR_INI_SIZE || $uploadFile['error'] == UPLOAD_ERR_FORM_SIZE) {
    echo "<span class='displayUploadMessage'>Failed to upload image. File size too large.</span>";
  }
  else {
    echo "<span class='displayUploadMessage'>Failed to upload image.</span>";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all"/>
  <title>"K-Pop Albums – Photo Gallery"</title>
</head>

<body>
  <div id="stickyHeader">
    <div id="navigationBar">
      <span class="navigationHome"><a href="index.php">K-POP ALBUMS – A Photo Gallery</a></span>
      <?php include("includes/header.php"); ?>
    </div> <!-- end of navigationBar div -->
  </div> <!-- end of stickyHeader div -->

  <div id="container">
    <div id="content">
      <h1 id="titleStyle">MY GALLERY</h1>

      <div id="displayPrivate">
        <?php
          if ($currentUser) {
        ?>
            <form id="formUpload" action="private.php" method="post" enctype="multipart/form-data">
              <ul>
                <li>
                  <label>Upload Image:</label>
                  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
                  <input type="file" name="imageFile" accept="image/*" required>
                </li>
                <li>
                  <label>Name of Image:</label>
                  <input type="text" name="imageTitle" required>
                </li>
                <li>
                  <label>Tags:</label>
                  <textarea name="imageTags" cols="24" rows="4" placeholder="separate tags by commas; duplicate tags will not be added" required></textarea>
                </li>
                <li>
                  <button type="submit" name="upload">Upload</button>
                </li>
              </ul>
            </form> <!-- end of formLogin -->
            <div id="displayUserImages">
              <?php
                $sqlUserID = "SELECT id from users WHERE username='$currentUser'";
                $resultsUserID = exec_sql_query($db, $sqlUserID)->fetchAll();
                $userID = $resultsUserID[0]['id'];
                $sqlUserImages = "SELECT * FROM images WHERE user_id='$userID'";
                $recordsUserImages = exec_sql_query($db, $sqlUserImages)->fetchAll();
                if (isset($recordsUserImages) and !empty($recordsUserImages)) {
                  foreach ($recordsUserImages as $record) {
                    echo "<a href='display.php?imageID=" . $record['id'] . "'><img src='" . GALLERY_UPLOADS_PATH . $record['id'] . "." . $record['ext'] . "'></a>";
                  }
                };
              ?>
            </div> <!-- end of displayUserImages div -->
      <?php
        }
        else {
          echo "<span class='displaySessionMessage'>Please <a href='session.php'>log in</a>.</span>";
        }
      ?>
      </div> <!-- end of displayPrivate div -->

    </div> <!-- end of content div -->
  </div> <!-- end of container div -->
</body>
</html>
