

<?php


  session_start();
  // Unset only the user session variable
  if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
  }

  echo "<script>window.open('../index.php','_self');</script>";

 ?>
