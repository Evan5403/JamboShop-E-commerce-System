<?php

  $conn = mysqli_connect('localhost', 'root', '', 'mystore');
  // if ($conn) {
  //   echo "connected";
  // }
  if(!$conn){
    die(mysqli_error($conn));
  }


 ?>
