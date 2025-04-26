<?php

  if (isset($_GET['edit_slide'])) {
    $slide_id = $_GET['edit_slide'];
    $sql_query = "SELECT
                    s.slide_id,
                    s.image_cover,
                    s.header_title,
                    s.mini_title,
                    s.description	,
                    s.category_id,
                    s.created_date,
                    s.status,
                    c.category_id,
                    c.category_title
                  FROM
                      slides s
                  LEFT JOIN
                    (
                      SELECT
                      category_id,
                      category_title
                    FROM
                      categories
                    ) c
                  ON
                    s.category_id = c.category_id
                  WHERE s.slide_id = '$slide_id'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_assoc($result);
    $image_cover = $row['image_cover'];
    $header_title = $row['header_title'];
    $mini_title = $row['mini_title'];
    $description = $row['description'];
    $category_id = $row['category_id'];
    $created_date = $row['created_date'];
    $category_title = $row['category_title'];
    $status = $row['status'];
  }

 ?>


<div class="container mt-5">
  <h1 class="text-center">Edit Slide</h1>
  <form class="" action="" method="post" enctype="multipart/form-data">
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_image1" class="form-label">Image Cover</label>
      <div class="d-flex">
        <input type="file" name="image_cover" value="" class="form-control w-90 m-auto">
        <img src="../imgs/<?php echo $image_cover ?>" alt="productimage" class='product_img m-2'>
      </div>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="header_title" class="form-label">Header Title</label>
      <input type="text" name="header_title" value="<?php echo $header_title ?>" class="form-control" autocomplete="off" required>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="mini_title" class="form-label">Mini Title</label>
      <input type="text" name="mini_title" value="<?php echo $mini_title ?>" class="form-control" autocomplete="off" required>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="description" class="form-label"> Description</label>
      <textarea name="description" rows="8" cols="80" class="form-control" required><?php echo $description ?></textarea>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_category" class="form-label">Applicable Category</label><br>
      <select class="form-select" name="category_id">
        <option value="<?php echo $category_id ?>"><?php echo $category_title ?></option>
        <?php
          $fetch_all_categories = "SELECT * FROM `categories` WHERE category_id != '$category_id' ORDER BY category_title ASC";
          $result_query = mysqli_query($conn,$fetch_all_categories);
          while ($row_category = mysqli_fetch_assoc($result_query)) {
            $category_id = $row_category['category_id'];
            $category_title = $row_category['category_title'];
            echo "<option value='$category_id'>$category_title</option>";
          }

         ?>
      </select>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="status" class="form-label">Status</label>
      <select class="form-select " name="status" id="status">
        <option value="<?php echo $status ?>"><?php echo $status ?></option>
        <?php
           if ($status == 'inactive') { ?>
             <option value="active">Activate</option>
           <?php } else { ?>
             <option value="inactive">Deactivate</option>
           <?php } ?>
      </select>
    </div>
    <div class="w-50 m-auto">
      <input type="submit" name="edit_slide" value="Update Slide" class="btn btn-info px-3 mb-4">
    </div>

  </form>
</div>

<?php

  if (isset($_POST['edit_slide'])) {
    $header_title = htmlspecialchars($_POST['header_title'], ENT_QUOTES, 'UTF-8');
    $mini_title = htmlspecialchars($_POST['mini_title'], ENT_QUOTES, 'UTF-8');
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $form_status = $_POST['status'];

    $deactivated = 'nothing';
    if ($status == 'inactive' && $form_status == 'active') {
      $deactivated = 'yes';
    }elseif ($status == 'active' && $form_status == 'inactive') {
      $deactivated = 'no';
    }

    $current_image = $row['image_cover'];

    // Handle the uploaded image
    $updated_image_cover = $_FILES['image_cover']['name'];
    $tmp_image1 = $_FILES['image_cover']['tmp_name'];

    // Check if a new image was uploaded
    if (!empty($updated_image_cover)) {
        $file_ext = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (in_array($_FILES['image_cover']['type'], $file_ext)) {
            move_uploaded_file($tmp_image1, "../product_imgs/$updated_image_cover");
        } else {
            ?>
            <script>
              Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Please insert a valid image",
              });
            </script>
            <?php
            exit; // Stop further execution if the image is invalid
        }
    } else {
        $updated_image_cover = $current_image; // Retain the current image
    }

    // var_dump($_POST, $_FILES);
    // die;

    // Update the product details in the database
    $update_slide = "UPDATE `slides`
                       SET
                          image_cover='$updated_image_cover',
                          header_title='$header_title',
                          mini_title='$mini_title',
                          description='$description',
                          category_id='$category_id',
                          status='$form_status'
                       WHERE slide_id='$slide_id'";
    $result_update = mysqli_query($conn, $update_slide);

    if ($result_update) {
      if ($deactivated == 'yes') {
        $action = "Activated Slide";
        $action_effect = "positive";
        $details = "Slide Title: $header_title"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }elseif ($deactivated == 'no') {
        $action = "Deactivated Slide";
        $action_effect = "negative";
        $details = "Slide Title: $header_title";// Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }
        ?>
        <script>
          Swal.fire({
            position: "top",
            icon: "success",
            title: "Updated!",
            text: "Product Updated Successfully",
            showConfirmButton: false,
            timer: 2300
          }).then(() => {
            window.open('index.php?edit_slide=<?php echo $slide_id ?>', '_self');
          });
        </script>
        <?php
    }
  }


 ?>
