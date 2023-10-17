<?php
  if (isset($_SESSION['message'])) {
      // Display message to the user
      echo "<div class='info_container " . $_SESSION['message_type'] . "'>" . $_SESSION['message_icon'] . " " . $_SESSION['message'] . "</div></br>";
      // After displaying the message, unset the session variables
      unset($_SESSION['message']);
      unset($_SESSION['message_type']);
      unset($_SESSION['message_icon']);
  }
?>