<?php

  include('../includes/connect.php');

  if (isset($_POST['insert_brand'])) {
    $brand_title = htmlspecialchars($_POST['brand_title'], ENT_QUOTES, 'UTF-8');

    // check if category already exist in the dbase else insert the category
    $select_query = "SELECT * FROM `brands` WHERE brand_title ='$brand_title'";
    $result_select = mysqli_query($conn, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
      // Brand already exists, set modal variables
      ?>
    <script>
      Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "This brand already exists!",
          });
    </script>
    <?php
    }else {
      $insert_query = "INSERT INTO `brands` (brand_title) VALUES ('$brand_title')";
      $result = mysqli_query($conn, $insert_query);
      if ($result) {
        $action = "Inserted a New Brand";
        $action_effect = "positive";
        $details = "Brand Name: $brand_title"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        ?>
        <script>
          Swal.fire({
            title: "Success!",
            text: "Brand inserted successfully",
            icon: "success"
          });
        </script>
      <?php
      }
    }

  }

 ?>
<h2 class="text-center"> Insert Brands</h2>
<form class="mb-2" action="" method="post">
  <div class="input-group w-90 mb-2">
    <span class="input-group-text bg-info" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
    <input type="text" class="form-control" name="brand_title" placeholder="Insert Brand" aria-label="Brands" aria-describedby="basic-addon1" autocomplete="off" required>
  </div>
  <div class="input-group w-10 mb-2 m-auto">
    <input type="submit" class="bg-info border-0 p-2 my-2" name="insert_brand" value="Insert Brand" >
  </div>
</form>
