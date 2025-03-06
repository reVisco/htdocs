<?php
session_start();
// If the session variable 'loggedIn' is not set (user not logged in)
if (!isset($_SESSION['loggedIn'])) {
  // Redirect the user to the login page (or desired restricted access page)
  header('Location: ../../index.php');
  exit;
}

// If the user is logged in, continue with the rest of your admin dashboard content.
