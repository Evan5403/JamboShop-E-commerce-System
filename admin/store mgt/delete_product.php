<?php

if (isset($_GET['delete_product'])){
  $product_id = $_GET['delete_product'];
  $stmt = $conn->prepare("SELECT * FROM `products` WHERE product_id  = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $product_title = $row['product_title'];
}

 ?>

<h3 class="text-danger mb-4">Are You Sure You Want To Delete Product</h3>

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
    $delete_query = "DELETE FROM `products` WHERE product_id='$product_id'";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted a product";
      $action_effect = "negative";
      $details = "Product Name: $product_title"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Product Deleted From Database Successfully!",
          text: "",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?view_products','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?view_products','_self')</script>";
  }
 ?>
