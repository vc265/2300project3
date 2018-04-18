<?php
$pageCurrent = "public gallery";
include("includes/init.php");
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
      <h1 id="titleStyle">PUBLIC GALLERY</h1>

      <div id="displayAllTags">
        <?php
          $sqlAllTags = "SELECT * FROM tags ORDER BY tag ASC";
          $recordsAllTags = exec_sql_query($db, $sqlAllTags)->fetchAll();
          echo "All Tags: ";
          if (isset($recordsAllTags) and !empty($recordsAllTags)) {
            foreach ($recordsAllTags as $record) {
              echo "<a href='display.php?tagID=" . $record['id'] . "'>" . $record['tag'] . "</a> ";
            }
          };
          echo "<br><br>";
        ?>
      </div> <!-- end of displayAllTags div -->
      <div id="displayAllImages">
        <?php
          $sqlAllImages = "SELECT * FROM images";
          $recordsAllImages = exec_sql_query($db, $sqlAllImages)->fetchAll();
          if (isset($recordsAllImages) and !empty($recordsAllImages)) {
            foreach ($recordsAllImages as $record) {
              echo "<a href='display.php?imageID=" . $record['id'] . "'><img src='" . GALLERY_UPLOADS_PATH . $record['id'] . "." . $record['ext'] . "'></a>";
            }
          };
        ?>
      </div> <!-- end of displayAllImages div -->

    </div> <!-- end of content div -->
  </div> <!-- end of container div -->
</body>
</html>
