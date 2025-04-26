<?php

  if (isset($_GET['edit_product'])) {
    $product_id = $_GET['edit_product'];
    $sql_query = "SELECT * FROM `products` WHERE product_id=$product_id";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_assoc($result);
    $product_title = $row['product_title'];
    $product_description = $row['product_description'];
    $demographic = $row['demographic'];
    $category_id = $row['category_title'];
    $brand_id = $row['brand_title'];
    $product_image1 = $row['product_image1'];
    $instock = $row['instock'];
    $product_price = $row['price'];
    $demographic  = $row['demographic'];

    // fetch categories
    $fetch_category = "SELECT * FROM `categories` WHERE category_id=$category_id";
    $result_category = mysqli_query($conn,$fetch_category);
    $row_category = mysqli_fetch_assoc($result_category);
    $category_title = $row_category['category_title'];

    // fetch brands
    $fetch_brands = "SELECT * FROM `brands` WHERE brand_id=$brand_id";
    $result_brands = mysqli_query($conn,$fetch_brands);
    $row_brand = mysqli_fetch_assoc($result_brands);
    $brand_title = $row_brand['brand_title'];
  }

 ?>


<div class="container mt-5">
  <h1 class="text-center">Edit Product</h1>
  <form class="" action="" method="post" enctype="multipart/form-data">
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_image1" class="form-label">Product Image</label>
      <div class="d-flex">
        <input type="file" name="product_image1" value="" class="form-control w-90 m-auto">
        <img src="../imgs/<?php echo $product_image1 ?>" alt="productimage" class='product_img m-2'>
      </div>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_title" class="form-label">Product Title</label>
      <input type="text" name="product_title" value="<?php echo $product_title ?>" class="form-control" autocomplete="off" required>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_description" class="form-label">Product Description</label>
      <!-- <input type="text" name="product_description" value="" class="form-control" required> -->
      <textarea name="product_description" rows="8" cols="80" class="form-control" required><?php echo $product_description ?></textarea>
    </div>
    <!-- gender -->
    <div class="form-outline mb-3 w-50 m-auto">
      <label for="demographic" class="form-label">Gender</label><br>
      <select class="form-select " name="demographic" id="" required>
        <option value="<?php echo $demographic ?>"><?php echo $demographic ?></option>
        <?php
        $fetch_gender = "SELECT DISTINCT demographic FROM products WHERE LOWER(demographic) != LOWER('$demographic')";
        $result_gender = mysqli_query($conn, $fetch_gender);
        if ($result_gender) {
          while ($row_gender = mysqli_fetch_assoc($result_gender)) {
            $other_demographic = $row_gender['demographic']; // Use a different variable
            echo "<option value='$other_demographic'>$other_demographic</option>";
          }
        } else {
          echo "Query failed: " . mysqli_error($conn);
        }
         ?>
      </select>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_category" class="form-label">Product Category</label><br>
      <select class="form-select" name="product_category">
        <option value="<?php echo $category_id ?>"><?php echo $category_title ?></option>
        <?php
          $fetch_all_categories = "SELECT * FROM `categories`";
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
      <label for="product_brands" class="form-label">Product Brands</label><br>
      <select class="form-select" name="product_brands">
        <option value="<?php echo $brand_id ?>"><?php echo $brand_title ?></option>
        <?php
          $fetch_all_brands = "SELECT * FROM `brands` WHERE brand_title != '$brand_title'";
          $result_query = mysqli_query($conn,$fetch_all_brands);
          while ($row_brand = mysqli_fetch_assoc($result_query)) {
            $brand_id = $row_brand['brand_id'];
            $brand_title = $row_brand['brand_title'];
            echo "<option value='$brand_id'>$brand_title</option>";
          }

         ?>
      </select>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_price" class="form-label">Update Product Stock</label>
      <input type="number" name="instock" value="<?php echo $instock ?>" min="<?php echo $instock ?>" class="form-control" required>
    </div>
    <div class="form-outline w-50 m-auto mb-4">
      <label for="product_price" class="form-label">Update Product Price (<b>Kshs.</b>)</label>
      <input type="number" name="product_price" value="<?php echo $product_price ?>" class="form-control" required>
    </div>
    <div class="w-50 m-auto">
      <input type="submit" name="edit_product" value="Update Product" class="btn btn-info px-3 mb-4">
    </div>

  </form>
</div>

<?php

  if (isset($_POST['edit_product'])) {
    $product_title = htmlspecialchars($_POST['product_title'], ENT_QUOTES, 'UTF-8');
    $product_description = htmlspecialchars($_POST['product_description'], ENT_QUOTES, 'UTF-8');
    $demographic = $_POST['demographic'];
    $product_category = $_POST['product_category'];
    $product_brands = $_POST['product_brands'];
    $instock = $_POST['instock'];
    $product_price = $_POST['product_price'];

    $stmt = $conn->prepare("SELECT * FROM `products` WHERE product_title  = ? AND product_id != ?");
    $stmt->bind_param("si", $product_title, $product_id);
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
          text: "Product Already Exists",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      die;
    }

    $current_image = $row['product_image1'];

    // Handle the uploaded image
    $product_image1 = $_FILES['product_image1']['name'];
    $tmp_image1 = $_FILES['product_image1']['tmp_name'];

    // Check if a new image was uploaded
    if (!empty($product_image1)) {
        $file_ext = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
        if (in_array($_FILES['product_image1']['type'], $file_ext)) {
            move_uploaded_file($tmp_image1, "../product_imgs/$product_image1");
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
        $product_image1 = $current_image; // Retain the current image
    }

    // Update the product details in the database
    $update_product = "UPDATE `products`
                       SET
                          product_title='$product_title',
                          product_description='$product_description',
                          demographic='$demographic',
                          category_title='$product_category',
                          brand_title='$product_brands',
                          instock='$instock',
                          price='$product_price',
                          product_image1='$product_image1'
                       WHERE product_id='$product_id'";
    $result_update = mysqli_query($conn, $update_product);

    if ($result_update) {
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
            window.open('index.php?edit_product=<?php echo $product_id ?>', '_self');
          });
        </script>
        <?php
    }
  }


 ?>
