<?php
  session_start();
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  if (!isset($_SESSION['admin'])) {
    echo "<script>window.open('admin_login.php','_self');</script>";
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
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adm Dashboard</title>

    <!-- bootstrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- remix icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css">
    <!-- css file -->
    <link rel="stylesheet" href="../style.css">

    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style media="screen">
      .product_img{
        width: 100px;
        height: auto;
        object-fit: contain;
      }
      .header{
        color: #fff;
        font-weight: bold;
      }
      .display_img{
        width: 60px;
        height: 60px;
        object-fit: contain;
      }
      .display_products{
        border-bottom: 1px solid grey;
      }
      ul {
        max-height: 300px; /* Set the maximum height of the list */
        overflow-y: auto; /* Add vertical scroll when content exceeds max height */
        border: 1px solid #ccc; /* Optional: Add a border for better visibility */
        padding: 10px; /* Optional: Add some padding */
        margin: 0; /* Remove default margin */
        list-style-type: none; /* Remove bullet points */
      }

      ul li {
        margin-bottom: 5px; /* Optional: Add spacing between list items */
      }
    </style>

  </head>
  <body>
    <!-- nabar -->
    <div class="container-fluid p-0">
      <!-- 1st child -->
      <div class="bg-light">
        <h3 class="text-center p-2">Manage Store</h3>
      </div>
      <!-- 3rd child -->
      <div class="row">
        <div class="col-md-12 bg-secondary p1 d-flex align-items-center">
          <div class="px-3">
            <a href="#"><img src="Admin Dashboard/admin_imgs/<?php echo $profile_image ?>" class="adm_img mt-2"></a>
            <p class="text-center text-light">@<?php echo $user_name ?></p>
          </div>

          <?php
            if ($role == 'store_manager') {
              ?>
              <!-- Store Manager -->
              <div class="button text-center">
                <button class="my-3"><a href="store mgt/insert_product.php" class="nav-link text-light bg-info my-1 p-3" target="_blank">Insert Products</a></button>
                <button><a href="index.php?view_products" class="nav-link text-light bg-info my-1 p-3">View Products</a></button>
                <button><a href="index.php?insert_department" class="nav-link text-light bg-info my-1 p-3">Insert Departments</a></button>
                <button><a href="index.php?view_department" class="nav-link text-light bg-info my-1 p-3">View Departments</a></button>
                <button><a href="index.php?insert_category" class="nav-link text-light bg-info my-1 p-3">Insert Categories</a></button>
                <button><a href="index.php?view_category" class="nav-link text-light bg-info my-1 p-3">View Categories</a></button>
                <button><a href="index.php?insert_brand" class="nav-link text-light bg-info my-1 p-3">Insert Brands</a></button>
                <button><a href="index.php?view_brands" class="nav-link text-light bg-info my-1 p-3">View Brands</a></button>
                <button><a href="index.php?list_orders" class="nav-link text-light bg-info my-1 p-3">All Orders</a></button>
                <button><a href="Admin Dashboard/admin_profile.php?store_manager" class="nav-link text-light bg-info my-1 p-3">Dashboard</a></button>
                <button><a href="Admin Dashboard/admin_logout.php" class="nav-link text-light bg-info my-1 p-3">Logout</a></button>
              </div>
            <?php } ?>

            <?php
              if ($role == 'admin') {
                ?>
                <!-- Store Manager -->
                <div class="button text-center">
                  <button class="my-3"><a href="store mgt/insert_product.php" class="nav-link text-light bg-info my-1 p-3" target="_blank">Insert Products</a></button>
                  <button><a href="index.php?view_products" class="nav-link text-light bg-info my-1 p-3">View Products</a></button>
                  <button><a href="index.php?insert_department" class="nav-link text-light bg-info my-1 p-3">Insert Departments</a></button>
                  <button><a href="index.php?view_department" class="nav-link text-light bg-info my-1 p-3">View Departments</a></button>
                  <button><a href="index.php?insert_category" class="nav-link text-light bg-info my-1 p-3">Insert Categories</a></button>
                  <button><a href="index.php?view_category" class="nav-link text-light bg-info my-1 p-3">View Categories</a></button>
                  <button><a href="index.php?insert_brand" class="nav-link text-light bg-info my-1 p-3">Insert Brands</a></button>
                  <button><a href="index.php?view_brands" class="nav-link text-light bg-info my-1 p-3">View Brands</a></button>
                  <button><a href="index.php?list_orders" class="nav-link text-light bg-info my-1 p-3">All Orders</a></button>
                </div>

                <!-- Marketing Team -->
                <div class="button text-center">
                  <button class="my-3"><a href="index.php?add_promotions" class="nav-link text-light bg-info my-1 p-3">Add Promotions</a></button>
                  <button><a href="index.php?view_promotions" class="nav-link text-light bg-info my-1 p-3">View Promotions</a></button>
                  <button><a href="index.php?add_flashsale" class="nav-link text-light bg-info my-1 p-3">Add Flashsales</a></button>
                  <button><a href="index.php?view_flashsale" class="nav-link text-light bg-info my-1 p-3">Flashsales List</a></button>
                  <button><a href="index.php?add_coupon" class="nav-link text-light bg-info my-1 p-3">Add Coupons</a></button>
                  <button><a href="index.php?view_coupons" class="nav-link text-light bg-info my-1 p-3">Coupons List</a></button>
                </div>

                <div class="row my-20">
                  <div class="col-md-12 bg-secondary p1 d-flex align-items-center px-3">
                    <button><a href="Admin Dashboard/admin_profile.php" class="nav-link text-light bg-info my-1 p-3">Dashboard</a></button>
                    <button><a href="Admin Dashboard/admin_logout.php" class="nav-link text-light bg-info my-1 p-3 ml-3">Logout</a></button>
                  </div>
                </div>
              <?php } ?>

              <?php
                if ($role == 'marketer') {
                  ?>
                  <!-- Marketing Team -->
                  <div class="button text-center">
                    <button class="my-3"><a href="index.php?add_promotions" class="nav-link text-light bg-info my-1 p-3">Add Promotions</a></button>
                    <button><a href="index.php?view_promotions" class="nav-link text-light bg-info my-1 p-3">View Promotions</a></button>
                    <button><a href="index.php?add_flashsale" class="nav-link text-light bg-info my-1 p-3">Add Flashsales</a></button>
                    <button><a href="index.php?view_flashsale" class="nav-link text-light bg-info my-1 p-3">Flashsales List</a></button>
                    <button><a href="index.php?add_slide" class="nav-link text-light bg-info my-1 p-3">Add Slide</a></button>
                    <button><a href="index.php?manage_slides" class="nav-link text-light bg-info my-1 p-3">Manage Slides</a></button>
                    <button><a href="Admin Dashboard/admin_profile.php?analytics" class="nav-link text-light bg-info my-1 p-3">Dashboard</a></button>
                    <button><a href="Admin Dashboard/admin_logout.php" class="nav-link text-light bg-info my-1 p-3">Logout</a></button>
                  </div>
                <?php } ?>
        </div>
      </div>

      <!-- fourth child -->
      <div class="container my-3">
        <?php
          // STORE MANAGER
          if (isset($_GET['insert_department'])) {
            include('store mgt/insert_department.php');
          }
          if (isset($_GET['insert_category'])) {
            include('store mgt/insert_categories.php');
          }
          if (isset($_GET['insert_brand'])) {
            include('store mgt/insert_brands.php');
          }
          if (isset($_GET['add_address'])) {
            include('store mgt/add_address.php');
          }
          if (isset($_GET['view_products'])) {
            include('store mgt/view_products.php');
          }
          if (isset($_GET['edit_product'])) {
            include('store mgt/edit_product.php');
          }
          if (isset($_GET['delete_product'])) {
            include('store mgt/delete_product.php');
          }
          if (isset($_GET['view_department'])) {
            include('store mgt/view_department.php');
          }
          if (isset($_GET['view_category'])) {
            include('store mgt/view_category.php');
          }
          if (isset($_GET['view_brands'])) {
            include('store mgt/view_brands.php');
          }
          if (isset($_GET['edit_department'])) {
            include('store mgt/edit_department.php');
          }
          if (isset($_GET['edit_category'])) {
            include('store mgt/edit_category.php');
          }
          if (isset($_GET['edit_brand'])) {
            include('store mgt/edit_brand.php');
          }
          if (isset($_GET['delete_department'])) {
            include('store mgt/delete_department.php');
          }
          if (isset($_GET['delete_category'])) {
            include('store mgt/delete_category.php');
          }
          if (isset($_GET['delete_brand'])) {
            include('store mgt/delete_brand.php');
          }
          if (isset($_GET['list_orders'])) {
            include('store mgt/list_orders.php');
          }

          // MARKETING
          if (isset($_GET['add_promotions'])) {
            include('marketing/add_promotions.php');
          }
          if (isset($_GET['add_flashsale'])) {
            include('marketing/add_flashsale.php');
          }
          if (isset($_GET['add_slide'])) {
            include('marketing/add_slide.php');
          }

          if (isset($_GET['view_promotions'])) {
            include('marketing/view_promotions.php');
          }
          if (isset($_GET['view_flashsale'])) {
            include('marketing/view_flashsale.php');
          }

          if (isset($_GET['edit_promotion'])) {
            include('marketing/edit_promotion.php');
          }
          if (isset($_GET['delete_promotion'])) {
            include('marketing/delete_promotion.php');
          }
          if (isset($_GET['edit_flashsale'])) {
            include('marketing/edit_flashsale.php');
          }
          if (isset($_GET['delete_flashsale'])) {
            include('marketing/delete_flashsale.php');
          }
          if (isset($_GET['manage_slides'])) {
            include('marketing/manage_slides.php');
          }
          if (isset($_GET['edit_slide'])) {
            include('marketing/edit_slide.php');
          }
          if (isset($_GET['delete_slide'])) {
            include('marketing/delete_slide.php');
          }
        ?>
      </div>
    </div>

    <!-- bootstrap jquery link -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  </body>
</html>
