
<?php

  include('../includes/connect.php');

  if (isset($_POST['insert_department'])) {
    $department_title = htmlspecialchars($_POST['department_title'], ENT_QUOTES, 'UTF-8');

    // check if category already exist in the dbase else insert the category
    $select_query = "SELECT * FROM `department` WHERE department_title ='$department_title'";
    $result_select = mysqli_query($conn, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
      // Category already exists, set modal variables
      ?>
    <script>
      Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "This department already exists!",
          });
    </script>
    <?php
    }else {
      $insert_query = "INSERT INTO `department` (department_title) VALUES ('$department_title')";
      $result = mysqli_query($conn, $insert_query);
      if ($result) {
        $action = "Inserted a New Department";
        $action_effect = "positive";
        $details = "Department Name: $department_title"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        ?>
        <script>
          Swal.fire({
            title: "Success!",
            text: "Department inserted successfully",
            icon: "success"
          });
        </script>
      <?php
      }
    }

  }

 ?>
<h2 class="text-center"> Insert Department </h2>
<form class="mb-2" action="" method="post">
  <div class="input-group w-90 mb-2">
    <span class="input-group-text bg-info" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
    <input type="text" class="form-control" name="department_title" placeholder="Insert Department" aria-label="Categories" aria-describedby="basic-addon1" required>
  </div>
  <div class="input-group w-10 mb-2 m-auto">
    <input type="submit" class="bg-info border-0 p-2 my-2" name="insert_department" value="Insert Department" >
  </div>
</form>
