<?php

  if (isset($_GET['delete_category'])) {
    $category_id = $_GET['delete_category'];
    $stmt = $conn->prepare("SELECT * FROM `categories` WHERE category_id  = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $category_title = $row['category_title'];
  }
?>

<h3 class="text-danger mb-4">Are You Sure You Want To Delete Category</h3>

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
    $delete_query = "DELETE FROM `categories` WHERE category_id='$category_id'";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted a Category";
      $action_effect = "negative";
      $details = "Brand Name: $category_title"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Category Deleted From Database Successfully!",
          text: "",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?view_category','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?view_category','_self')</script>";
  }
 ?>
