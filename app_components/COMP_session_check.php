<?php
  session_start();

  // check to see if the user is logged in
  if (!isset($_SESSION['usr_id'])) {
    header("Location: ../intranet/usr_actions.php?action=expell");
    exit();
  }
?>