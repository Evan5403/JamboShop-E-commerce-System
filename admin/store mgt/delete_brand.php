<?php

  if (isset($_GET['delete_brand'])) {
    $brand_id = $_GET['delete_brand'];
    $stmt = $conn->prepare("SELECT * FROM `brands` WHERE brand_id  = ?");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $brand_title = $row['brand_title'];
  }
?>

<h3 class="text-danger mb-4">Are You Sure You Want To Delete Brand</h3>

<form class="mt-5" action="" method="post">
  <div class="form-outline mb-4">
    <input type="submit" name="delete" class="form-control w-50 m-auto" value="proceed">
  </div>
  <div class="form-outline mb-4">
    <input type="submit" name="cancel" class="form-control w-50 m-auto" value="cancel">
  </div>
</form>

<?php

  if (isset($_POST['delete'])) {
    $delete_query = "DELETE FROM `brands` WHERE brand_id='$brand_id'";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted a Brand";
      $action_effect = "negative";
      $details = "Brand Name: $brand_title"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Brand Deleted From Database Successfully!",
          text: "",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?view_brands','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?view_brands','_self')</script>";
  }
 ?>
