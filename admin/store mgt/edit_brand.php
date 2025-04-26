<?php
  if (isset($_GET['edit_brand'])) {
    $brand_id = $_GET['edit_brand'];

    $get_brand = "SELECT * FROM `brands` WHERE brand_id=$brand_id";
    $result_brand = mysqli_query($conn, $get_brand);
    $row = mysqli_fetch_assoc($result_brand);
    $brand_title = $row['brand_title'];
  }

 ?>


<div class="container mt-3">
  <h1 class="text-center">Edit Brand</h1>
  <form class="text-center" action="" method="post">
    <div class="form-outline mb-4 w-50 m-auto">
      <label for="brand_title" class="form-label">Brand Title</label>
      <input type="text" name="brand_title" value="<?php echo $brand_title ?>" id="brand_title" class="form-control" autocomplete="off" required>
    </div>
    <input type="submit" name="update_brand" value="Update Brand" class="btn btn-info px-3 mb-3">
  </form>
</div>


<?php

  if (isset($_POST['update_brand'])) {
    $brand_title = htmlspecialchars($_POST['brand_title'], ENT_QUOTES, 'UTF-8');

    // check if category exists
    $stmt = $conn->prepare("SELECT * FROM `brands` WHERE brand_title  = ? AND brand_id != ?");
    $stmt->bind_param("si", $brand_title, $brand_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows_count = $result->num_rows;
    if ($rows_count > 0) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Error...",
          text: "Brand Already Exists",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      die;
    }

    $update_brand = "UPDATE `brands` SET brand_title='$brand_title' WHERE brand_id=$brand_id";
    $result = mysqli_query($conn, $update_brand);
    if ($result) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Brand Updated Successfully",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?edit_brand=<?php echo $brand_id ?>','_self');
        });
      </script>
      <?php
    }
  }

 ?>
