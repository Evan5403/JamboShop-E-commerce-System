<?php

if (isset($_GET['delete_promotion'])){
  $promotion_id = $_GET['delete_promotion'];
  $get_promotion = "SELECT * FROM `promotions` WHERE promotion_id=$promotion_id";
  $result_promotion = mysqli_query($conn, $get_promotion);
  $row_promotion = mysqli_fetch_assoc($result_promotion);
  $promotion_name = $row_promotion['promotion_name'];
}

 ?>

<h3 class="text-danger text-center mb-4">Are You Sure You Want To Remove Promotion</h3>

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
    $delete_query = "DELETE FROM `promotions` WHERE promotion_id='$promotion_id'";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted Promotion";
      $action_effect = "negative";
      $details = "Promotion Name: $promotion_name"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Promotion Removed From Database Successfully!",
        }).then(() => {
          window.open('index.php?view_promotions','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?view_promotions','_self')</script>";
  }
 ?>
