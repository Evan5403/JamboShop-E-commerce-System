
<?php

  include('../includes/connect.php');

  if (isset($_POST['insert_cat'])) {
    $category_title = htmlspecialchars($_POST['cat_title'], ENT_QUOTES, 'UTF-8');
    $department_id = (int) $_POST['department'];
    // check if category already exist in the dbase else insert the category
    $select_query = "SELECT * FROM `categories` WHERE category_title ='$category_title'";
    $result_select = mysqli_query($conn, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
      // Category already exists, set modal variables
      ?>
    <script>
      Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "This category already exists!",
          });
    </script>
    <?php
    }else {
      $insert_query = "INSERT INTO `categories` (category_title,department_id) VALUES ('$category_title',$department_id)";
      $result = mysqli_query($conn, $insert_query);
      if ($result) {
        $action = "Inserted a New Category";
        $action_effect = "positive";
        $details = "Category Name: $category_title"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        ?>
        <script>
          Swal.fire({
            title: "Success!",
            text: "Category inserted successfully",
            icon: "success"
          });
        </script>
      <?php
      }
    }

  }

 ?>
<h2 class="text-center"> Insert Categories </h2>
<form class="mb-2" action="" method="post">
  <div class="input-group w-90 mb-2">
    <span class="input-group-text bg-info" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
    <input type="text" class="form-control" name="cat_title" placeholder="Insert Category" aria-label="Categories" aria-describedby="basic-addon1" autocomplete="off" required>
  </div>

  <div class="form-outline mb-3 ">
    <select class="form-select " name="department" id="">
      <option value="">Select Department</option>
      <?php
        $select_query = "SELECT * FROM `department`";
        $result = mysqli_query($conn, $select_query);
        while ($row = mysqli_fetch_assoc($result)) {
          $department_id  = $row['department_id'];
          $department_title = $row['department_title'];
          echo "<option value='$department_id'>$department_title</option>";
        }
       ?>
    </select>
  </div>
  <div class="input-group w-10 mb-2 m-auto">
    <input type="submit" class="bg-info border-0 p-2 my-2" name="insert_cat" value="Insert Category" >
  </div>
</form>
