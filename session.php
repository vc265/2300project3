<?php
$pageCurrent = "account";
include("includes/init.php");
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
      if ($currentUser) { ?>
        <form id='formLogout' action='session.php' method='post'>
          <ul>
            <li>
              <button name='logout' type='submit'>Log Out</button>
            </li>
          </ul>
        </form> <!-- end of formLogout -->
      <?php
      }
      else { ?>
        <form id='formLogin' action='session.php' method='post'>
         <ul>
           <li>
             <label>Username:</label>
             <input type='text' name='username' required/>
           </li>
           <li>
             <label>Password:</label>
             <input type='password' name='password' required/>
           </li>
           <li>
             <button name='login' type='submit'>Log In</button>
           </li>
         </ul>
       </form> <!-- end of formLogin -->
      <?php
      }
      ?>
    </div> <!-- end of content div -->
  </div> <!-- end of container div -->

</body>
</html>
