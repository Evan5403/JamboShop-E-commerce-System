<?php
  include('../../includes/connect.php');

  $select_query = "SELECT * FROM `department`";
  $result = mysqli_query($conn, $select_query);
  while ($row = mysqli_fetch_assoc($result)) {
    $department_title = $row['department_title'];
    $department_id = $row['department_id'];
    echo "<option value='$department_id'>$department_title</option>";
  }

 ?>
