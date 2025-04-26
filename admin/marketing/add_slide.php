<form class="" action="" method="post" enctype="multipart/form-data">
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="img_cover" class="form-label">Image Cover</label>
    <input type="file" name="img_cover" id="img_cover" class="form-control" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="header_title" class="form-label">Header Title</label>
    <input type="text" name="header_title" id="header_title" class="form-control" placeholder="Header Title" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="mini_title" class="form-label">Mini Title</label>
    <input type="text" name="mini_title" id="mini_title" class="form-control" placeholder="Start Sentence" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" class="form-control" id="description" rows="5" cols="80" required></textarea>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="category_id" class="form-label">Applicable Category</label>
    <select class="form-select " name="category_id" id="category_id" required>
      <option value=''>select applicable category</option>
      <?php
        $get_category = "SELECT * FROM categories ORDER BY `category_title` ASC";
        $result_cats = mysqli_query($conn, $get_category);
        if ($result_cats) {
          while ($row = mysqli_fetch_assoc($result_cats)) { ?>
            <option value='<?php echo $row['category_id'] ?>'><?php echo $row['category_title'] ?></option>
        <?php }} ?>

    </select>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <input type="submit" name="insert_slide" class="btn btn-info mb-4" value="Submit">
  </div>
</form>


<?php

  if (isset($_POST['insert_slide'])) {
    $header_title = htmlspecialchars($_POST['header_title'], ENT_QUOTES, 'UTF-8');
    $mini_title = htmlspecialchars($_POST['mini_title'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $category_id = htmlspecialchars($_POST['category_id'], ENT_QUOTES, 'UTF-8');

    $img_cover = $_FILES['img_cover']['name'];
    $tmp_image = $_FILES['img_cover']['tmp_name'];

    $file_ext = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
    if (in_array($_FILES['img_cover']['type'], $file_ext)) {
      move_uploaded_file($tmp_image, "../product_imgs/$img_cover");
      // INSERT SLIDE
      $insert_slide = "INSERT INTO `slides`
                            (image_cover,header_title,mini_title,description,category_id,created_date,status)
                          VALUES
                            ('$img_cover','$header_title','$mini_title','$description','$category_id',NOW(),'active')";
      $result = mysqli_query($conn, $insert_slide);
      if ($result) {
        $action = "Added A New Marketing Slide";
        $action_effect = "positive";
        $details = "Slide Title: $header_title"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Success!",
            text: "Slide inserted successfully!",
            icon: "success"
          });
        </script>
        <?php
      } else {
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Oops!",
            text: "Something Went Wrong!",
            icon: "warning"
          });
        </script>
        <?php
      }
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

  }

 ?>
