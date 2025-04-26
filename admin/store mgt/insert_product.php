<?php
  session_start();
  include('../../includes/connect.php');
  include('../../functions/common_functions.php');

  if (!isset($_SESSION['admin'])) {
    echo "<script>window.open('../admin_login.php','_self');</script>";
  } else {
    $admin = $_SESSION['admin'];
    $select_details = "SELECT * FROM admin_table WHERE user_name='$admin'";
    $exe_query = mysqli_query($conn, $select_details);
    $row_admin = mysqli_fetch_assoc($exe_query);
    $admin_id = $row_admin['admin_id'];
    $role = $row_admin['role'];
    $profile_image = $row_admin['profile_image'];
    $user_name = $row_admin['user_name'];
    $password = $row_admin['password'];
  }

  if ($role !== 'store_manager' AND $role !== 'admin') {
    echo "<script>window.open('../Admin Dashboard/admin_logout.php','_self');</script>";
  }

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adm Dashboard-Insert Products</title>

    <!-- bootstrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css file -->
    <link rel="stylesheet" href="../style.css">

    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </head>
  <body class="bg-light">
    <div class="container mt-3">
      <h1 class="text-center">Insert Product</h1>
      <!-- form -->
      <form class="" action="" method="post" id="product_form" enctype="multipart/form-data">
        <!-- p_img1 -->
        <div class="form-outline mb-3 w-50 m-auto">
          <label for="product_image1" class="form-label">Product Image</label>
          <input type="file" name="product_image1" id="product_image1" class="form-control" required>
        </div>
        <!-- p_brand -->
        <div class="form-outline mb-3 w-50 m-auto">
          <select class="form-select " name="product_brands" id="" required>
            <option value="">Select Brand</option>
            <?php
              $select_query = "SELECT * FROM `brands`";
              $result = mysqli_query($conn, $select_query);
              while ($row = mysqli_fetch_assoc($result)) {
                $brand_title = $row['brand_title'];
                $brand_id = $row['brand_id'];
                echo "<option value='$brand_id'>$brand_title</option>";
              }
             ?>
          </select>
        </div>
        <!-- p_department -->
        <div class="form-outline mb-3 w-50 m-auto">
          <select class="form-select " name="product_department" id="product_department" required>
            <option value="">Select Department</option>
          </select>
        </div>
        <!-- p_categories -->
        <div class="form-outline mb-3 w-50 m-auto">
          <select class="form-select " name="product_categories" id="product_categories" required>
            <option value="">Select Category</option>
          </select>
        </div>
        <!-- p_title -->
        <div class="form-outline mb-3 w-50 m-auto">
          <label for="product_title" class="form-label">Product Title</label>
          <input type="text" name="product_title" id="product_title" class="form-control" placeholder="Enter Product Title" autocomplete="off" required>
        </div>
        <!-- gender -->
        <div class="form-outline mb-3 w-50 m-auto">
          <select class="form-select " name="demographic" id="" required>
            <option value="">Select Gender</option>
            <option value='men'>Men</option>
            <option value='women'>Women</option>
            <option value='unisex'>Unisex</option>
            <option value='kids'>Kids</option>
          </select>
        </div>
        <!-- p_desc -->
        <div class="form-outline mb-3 w-50 m-auto">
          <label for="description" class="form-label">Product Description</label>
          <textarea name="description" id="description" class="form-control" placeholder="Enter Product Description" autocomplete="off" required rows="5" cols="20"></textarea>
          <!-- <input type="textarea" name="description" id="description" class="form-control" placeholder="Enter Product Description" autocomplete="off" required> -->
        </div>
        <!-- p_size -->
        <div class="form-outline mb-3 w-50 m-auto">
          <label for="p_size" class="form-label">Product Size</label>
          <select class="form-select " name="product_size" id="p_size" required>
            <option value="">Select Product Size</option>
            <option value="xs">XS</option>
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
            <option value="xxl">XXL</option>
            <option value="22EUR/4UK">22 EUR / 4 UK</option>
            <option value="23EUR/5UK">23 EUR / 5 UK</option>
            <option value="24EUR/6UK">24 EUR / 6 UK</option>
            <option value="25EUR/7UK">25 EUR / 7 UK</option>
            <option value="26EUR/8UK">26 EUR / 8 UK</option>
            <option value="27EUR/9UK">27 EUR / 9 UK</option>
            <option value="28EUR/10UK">28 EUR / 10 UK</option>
            <option value="29EUR/11UK">29 EUR / 11 UK</option>
            <option value="30EUR/12UK">30 EUR / 12 UK</option>
            <option value="31EUR/13UK">31 EUR / 13 UK</option>
            <option value="32EUR/1UK">32 EUR / 1 UK</option>
            <option value="33EUR/2UK">33 EUR / 2 UK</option>
            <option value="34EUR/3UK">34 EUR / 3 UK</option>
            <option value="35EUR/3.5UK">35 EUR / 3.5 UK</option>
            <option value="36EUR/4UK">36 EUR / 4 UK</option>
            <option value="37EUR/4.5UK">37 EUR / 4.5 UK</option>
            <option value="38EUR/5UK">38 EUR / 5 UK</option>
            <option value="39EUR/6UK">39 EUR / 6 UK</option>
            <option value="40EUR/6.5UK">40 EUR / 6.5 UK</option>
            <option value="41EUR/7UK">41 EUR / 7 UK</option>
            <option value="42EUR/8UK">42 EUR / 8 UK</option>
            <option value="43EUR/9UK">43 EUR / 9 UK</option>
            <option value="44EUR/9.5UK">44 EUR / 9.5 UK</option>
            <option value="45EUR/10UK">45 EUR / 10 UK</option>
            <option value="46EUR/11UK">46 EUR / 11 UK</option>
            <option value="47EUR/12UK">47 EUR / 12 UK</option>
            <option value="48EUR/13UK">48 EUR / 13 UK</option>

          </select>
        </div>
        <!-- p_img2 -->
        <!-- <div class="form-outline mb-3 w-50 m-auto">
          <label for="product_image2" class="form-label">Product Image2</label>
          <input type="file" name="product_image2" id="product_image2" class="form-control" required>
        </div> -->
        <!-- p_img3 -->
        <!-- <div class="form-outline mb-3 w-50 m-auto">
          <label for="product_image3" class="form-label">Product Image3</label>
          <input type="file" name="product_image3" id="product_image3" class="form-control" required>
        </div> -->
        <!-- p_qty -->
        <div class="form-outline mb-3 w-50 m-auto">
          <label for="instock" class="form-label">Instock</label>
          <input type="number" name="instock" id="instock" class="form-control" placeholder="Enter Product Quantity" autocomplete="off" required>
        </div>
        <!-- p_price -->
        <div class="form-outline mb-3 w-50 m-auto">
          <label for="product_price" class="form-label">Product Price (<b>Kshs</b>)</label>
          <input type="number" name="product_price" id="product_price" class="form-control" placeholder="Enter Product Price" autocomplete="off" required>
        </div>
        <div class="form-outline mb-3 w-50 m-auto">
          <input type="submit" name="insert_product" class="btn btn-info mb-4" value="Submit">
        </div>
      </form>
    </div>

    <script type="text/javascript">
      // Load departments on page load
      window.onload = function () {
        fetch("get_departments.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("product_department").innerHTML += data;
            })
            .catch(error => console.error("Error fetching departments:", error));
      };

      // Fetch categories based on the selected department
      document.getElementById("product_department").addEventListener("change", function () {
        const departmentId = this.value;

        // Clear the categories dropdown
        const categoryDropdown = document.getElementById("product_categories");
        categoryDropdown.innerHTML = '<option value="">Select Category</option>';

        if (departmentId) {
            fetch("get_categories.php", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: `department_id=${departmentId}`
            })
              .then(response => response.text())
              .then(data => {
                categoryDropdown.innerHTML += data;
              })
              .catch(error => console.error("Error fetching categories:", error));
        }
      });

    </script>
  </body>
</html>

<?php
  if (isset($_POST['insert_product'])) {
    $product_title = htmlspecialchars($_POST['product_title']);
    $demographic = $_POST['demographic'];
    $product_description = htmlspecialchars($_POST['description']);
    $product_category = $_POST['product_categories'];
    $get_cat_name = "SELECT * FROM categories WHERE category_id='$product_category'";
    $exe_query = mysqli_query($conn,$get_cat_name);
    $row_cat_name = mysqli_fetch_assoc($exe_query);
    $cat_name = $row_cat_name['category_title'];

    $product_brand = $_POST['product_brands'];
    $product_size = $_POST['product_size'];
    $instock = $_POST['instock'];
    $product_price = $_POST['product_price'];
    $product_status = 'true';

    $stmt = $conn->prepare("SELECT * FROM `products` WHERE product_title  = ?");
    $stmt->bind_param("s", $product_title);
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
    // access imgs
    $product_image1 = $_FILES['product_image1']['name'];
    // $product_image2 = $_FILES['product_image2']['name'];
    // $product_image3 = $_FILES['product_image3']['name'];
    // tmp img name
    $tmp_image1 = $_FILES['product_image1']['tmp_name'];
    // $tmp_image2 = $_FILES['product_image2']['tmp_name'];
    // $tmp_image3 = $_FILES['product_image3']['tmp_name'];
    $file_ext = ['image/jpeg','image/jpg','image/png','image/webp','image/avif'];

    if (in_array($_FILES['product_image1']['type'],$file_ext)) {
       // && in_array($_FILES['product_image2']['type'],$file_ext) && in_array($_FILES['product_image3']['type'],$file_ext)
      move_uploaded_file($tmp_image1,"../../product_imgs/$product_image1");
      // move_uploaded_file($tmp_image2,"../product_imgs/$product_image2");
      // move_uploaded_file($tmp_image3,"../product_imgs/$product_image3");
      // insert product
      $insert_products = "INSERT INTO `products`
                            (product_title,demographic,product_description,category_title,brand_title,product_image1,product_size,instock,price,date,status)
                          VALUES      ('$product_title','$demographic','$product_description','$product_category','$product_brand','$product_image1','$product_size','$instock','$product_price',NOW(),'$product_status')";
      $result = mysqli_query($conn, $insert_products);

      if ($result) {
        $action = "Added a New Product";
        $action_effect = "positive";
        $details = "Product Name: $product_title"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Success!",
            text: "Product inserted successfully!",
            icon: "success"
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
            text: "Please insert valid image",
        });
      </script>
      <?php
    }

  }
 ?>
