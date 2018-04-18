<?php
$pageList = array(
  "session" => "account",
  "private" => "my gallery",
  "index" => "public gallery",
);
?>

<header>
  <?php
  foreach($pageList as $pageFile => $pageTitle) { // checks to see what the current page is
    if ($pageTitle == $pageCurrent) { // the current page should be delinked and greyed out in the navigation bar
      $navigationLink = "class='navigationUnstyle'";
      echo "<span " . $navigationLink . ">" . $pageTitle . "</span>";
    }
    else { // the other pages should be linked in the navigation bar
      $navigationLink = "class='navigationStyle'";
      echo "<span " . $navigationLink . "><a href ='" . $pageFile . ".php'>" . $pageTitle . "</a></span>";
    }
  }
  ?>
</header>
