<?php

if (isset($_GET['delete_flashsale'])){
  $flash_sale_id = $_GET['delete_flashsale'];
  $get_flashsale = "SELECT * FROM `flash_sales` WHERE flash_sale_id=$flash_sale_id";
  $result_flashsale = mysqli_query($conn, $get_flashsale);
  $row_flashsale = mysqli_fetch_assoc($result_flashsale);
  $flashsale_name = $row_flashsale['flash_sale_name'];
}

 ?>

<h3 class="text-danger text-center mb-4">Are You Sure You Want To Remove Flashsale</h3>

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
    $delete_query = "DELETE FROM `flash_sales` WHERE flash_sale_id='$flash_sale_id'";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted Flashsale";
      $action_effect = "negative";
      $details = "Flashsale Name: $flashsale_name"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Flashsale Removed From Database Successfully!",
        }).then(() => {
          window.open('index.php?view_flashsale','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?view_flashsale','_self')</script>";
  }
 ?>
