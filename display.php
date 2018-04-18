<?php
$pageCurrent = "display";
include("includes/init.php");

$singleImageID = NULL; // variable represents current page's image ID
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all"/>
  <title>"K-pop Album Photo Gallery"</title>
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
      <?php
        if (isset($_GET['tagID'])) { // if an individual tag is clicked, display all images with that tag on new page
          $singleTagID = $_GET['tagID'];
          $sqlSingleTag = "SELECT * FROM tags WHERE id=$singleTagID";
          $recordsSingleTag = exec_sql_query($db, $sqlSingleTag)->fetchAll();
          echo "<h1 id='titleStyle'>TAG: " . $recordsSingleTag[0]['tag'] . "</h1>";
          $sqlSingleImages = "SELECT images.id, images.ext FROM images INNER JOIN gallery ON images.id=gallery.image_id INNER JOIN tags ON tags.id=gallery.tag_id WHERE tags.id=$singleTagID";
          $recordsSingleImages = exec_sql_query($db, $sqlSingleImages)->fetchAll();
          echo "<div id='displaySingleTag'>";
          if (isset($recordsSingleImages) and !empty($recordsSingleImages)) {
            foreach ($recordsSingleImages as $record) {
              echo "<a href='display.php?imageID=" . $record['id'] . "'><img src='" . GALLERY_UPLOADS_PATH . $record['id'] . "." . $record['ext'] . "'></a>";
            }
          };
          echo "</div>";
        }
        else if (isset($_GET['imageID']) || ($singleImageID)) { // if an individual image is clicked, display all tags for that image on new page
          $singleImageID = $_GET['imageID'];
          $sqlSingleImage = "SELECT * FROM images WHERE id=$singleImageID";
          $recordsSingleImage = exec_sql_query($db, $sqlSingleImage)->fetchAll();
          echo "<h1 id='titleStyle'>IMAGE: " . $recordsSingleImage[0]['title'] . "</h1>";
          echo "<div id='displaySingleImage'>";
          echo "<img src='" . GALLERY_UPLOADS_PATH . $recordsSingleImage[0]['id'] . "." . $recordsSingleImage[0]['ext'] . "'>";
          echo "<br><br>";
          $sqlSingleTags = "SELECT tags.id, tags.tag FROM tags INNER JOIN gallery ON tags.id=gallery.tag_id INNER JOIN images ON images.id=gallery.image_id WHERE images.id=$singleImageID ORDER BY tag ASC";
          $recordsSingleTags = exec_sql_query($db, $sqlSingleTags)->fetchAll();
          if (isset($recordsSingleTags) and !empty($recordsSingleTags)) {
            foreach ($recordsSingleTags as $record) {
              echo "<a href='display.php?tagID=" . $record['id'] . "'>" . $record['tag'] . "</a> ";
            }
          };
          echo "</div>";
      ?>
          <div id="displayActions">
            <form id="formAdd" method="post">
              <ul>
                <li>
                  <label>Add Tags:</label>
                  <textarea name="tags" cols="30" rows="5" placeholder="separate tags by commas; duplicate tags will not be added"></textarea>
                </li>
                <li>
                  <button type="submit" name="addTags">Submit</button>
                </li>
              </ul>
            </form> <!-- end of formAdd -->

          <?php
            if (isset($_POST['addTags'])) { // if a new tag is submitted
              $inputTags = strtolower(filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING));
              addTag($inputTags, $singleImageID);
              echo "<meta http-equiv='refresh' content='0'>"; // refreshes page after form is submitted and query is updated
              // if (count($arrayTags) != $countCombo) {
              //   echo "<span class='displayTagMessage'>Duplicate tag(s) were not added.</span>";
              // }
              // echo "<meta http-equiv='refresh' content='0'>";
            }
            if ($currentUser) {
              $sqlCheckUserID = "SELECT user_id FROM images WHERE id=$singleImageID";
              $recordsCheckUserID = exec_sql_query($db, $sqlCheckUserID)->fetchAll();
              $currentID = $recordsCheckUserID[0]['user_id'];
              $sqlCheckUsername = "SELECT username FROM users WHERE id=$currentID";
              $recordsCheckUsername = exec_sql_query($db, $sqlCheckUsername)->fetchAll();
              if ($currentUser == $recordsCheckUsername[0]['username']) { // if current user is uploaded user
          ?>
                <form id="formTag" method="post">
                  <select name="tagDelete">
                    <?php
                      if (isset($recordsSingleTags) and !empty($recordsSingleTags)) {
                        foreach ($recordsSingleTags as $record) {
                          echo "<option value='" . $record['tag'] . "'>" . $record['tag'] . "<br>";
                        }
                      };
                    ?>
                  </select>
                  <button type="submit" name="deleteTag">Delete Tag</button>
                </form> <!-- end of formTag form -->
                <form id="formImage" method="post">
                  <button type="submit" name="deleteImage">Delete Image</button>
                </form> <!-- end of formImage form -->
                <?php
                  if (isset($_POST['deleteTag'])) {
                    $selectedTag = $_POST['tagDelete'];
                    $sqlSelectedTag = "SELECT id FROM tags WHERE tag='$selectedTag'";
                    $resultSelectedTag = exec_sql_query($db, $sqlSelectedTag)->fetchAll();
                    $deleteTagID = $resultSelectedTag[0]['id'];
                    $sqlDeleteTag = "DELETE FROM gallery WHERE image_id=$singleImageID AND tag_id=$deleteTagID";
                    $resultDeleteTag = exec_sql_query($db, $sqlDeleteTag);
                    echo "<meta http-equiv='refresh' content='0'>"; // refreshes page after form is submitted and query is updated
                  }
                  else if (isset($_POST['deleteImage'])) {
                    $sqlUnlinkImage = "SELECT * FROM images WHERE id=$singleImageID";
                    $resultUnlinkImage = exec_sql_query($db, $sqlUnlinkImage)->fetchAll();
                    $unlinkImage = $resultUnlinkImage[0]['image'];
                    unlink("uploads/images/$unlinkImage");
                    $sqlDeleteImage = "DELETE FROM images WHERE id=$singleImageID";
                    $resultDeleteImage = exec_sql_query($db, $sqlDeleteImage);
                    $sqlDeleteCombo = "DELETE FROM gallery WHERE image_id=$singleImageID";
                    $resultDeleteCombo = exec_sql_query($db, $sqlDeleteCombo);
                    echo "<meta http-equiv='refresh' content='0; URL=index.php'>";
                  }
                  // if a tag is no longer used because it was deleted from an image or an image was deleted, remove tag completely from database
                  $sqlCheckTags = "SELECT * FROM tags";
                  $recordsCheckTags = exec_sql_query($db, $sqlCheckTags)->fetchAll();
                  foreach($recordsCheckTags as $record) {
                    $tagID = $record['id'];
                    $sqlCheckUsage = "SELECT * FROM gallery WHERE tag_id=$tagID";
                    $resultCheckUsage = exec_sql_query($db, $sqlCheckUsage)->fetchAll();
                    if (empty($resultCheckUsage)) {
                      $sqlRemoveID = "DELETE FROM tags WHERE id='$tagID'";
                      $resultRemoveID = exec_sql_query($db, $sqlRemoveID);
                    }
                  }
              }
            }
          echo "</div>";
        }
                ?>
    </div> <!-- end of content div -->
  </div> <!-- end of container div -->
</body>
</html>
