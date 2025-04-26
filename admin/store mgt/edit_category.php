<?php
  if (isset($_GET['edit_category'])) {
    $category_id = $_GET['edit_category'];

    $get_category = "SELECT * FROM `categories` WHERE category_id=$category_id";
    $result_category = mysqli_query($conn, $get_category);
    $row_category_title = mysqli_fetch_assoc($result_category);
    $category_title = $row_category_title['category_title'];
  }

 ?>


<div class="container mt-3">
  <h1 class="text-center">Edit Category</h1>
  <form class="text-center" action="" method="post">
    <div class="form-outline mb-4 w-50 m-auto">
      <label for="category_title" class="form-label">Category Title</label>
      <input type="text" name="category_title" value="<?php echo $category_title ?>" id="category_title" class="form-control" autocomplete="off" required>
    </div>
    <input type="submit" name="update_category" value="Update Category" class="btn btn-info px-3 mb-3">
  </form>
</div>


<?php

  if (isset($_POST['update_category'])) {
    $category_title = htmlspecialchars($_POST['category_title'], ENT_QUOTES, 'UTF-8');

    // check if category exists
    $stmt = $conn->prepare("SELECT * FROM `categories` WHERE category_title  = ? AND category_id != ?");
    $stmt->bind_param("si", $category_title, $category_id);
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
          text: "Category Already Exists",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      die;
    }

    $update_category = "UPDATE `categories` SET category_title='$category_title' WHERE category_id=$category_id";
    $result = mysqli_query($conn, $update_category);
    if ($result) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Category Updated Successfully",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?edit_category=<?php echo $category_id ?>','_self');
        });
      </script>
      <?php
    }
  }

 ?>
