<?php
  include('../../includes/connect.php');



  // Get the department ID from the POST request
  $department_id = $_POST['department_id'] ?? null;

  if ($department_id) {
      // Fetch categories based on department_id
      $select_query = "SELECT * FROM categories WHERE department_id = $department_id";
      $result = mysqli_query($conn, $select_query);

      // Output category options
      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $category_title = $row['category_title'];
            $category_id = $row['category_id'];
            echo "<option value='$category_id'>$category_title</option>";
          }
      } else {
          echo '<option value="">No Categories Available</option>';
      }
  } else {
      echo '<option value="">Invalid Department</option>';
  }



 ?>
