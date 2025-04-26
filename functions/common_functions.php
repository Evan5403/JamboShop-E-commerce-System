<?php

  // include('./includes/connect.php');

  // get products
  function getproducts(){
    global $conn;

    // condition to check if category/ brand is present
    if (!isset($_GET['category'])) {
      if (!isset($_GET['brand'])) {
        $select_query = "SELECT * FROM `products` ORDER BY rand() LIMIT 0,6";
        $result = mysqli_query($conn, $select_query);
        while ($row = mysqli_fetch_assoc($result)) {
          $product_id = $row['product_id'];
          $product_title = $row['product_title'];
          $product_description = $row['product_description'];
          $product_image1 = $row['product_image1'];
          $instock = $row['instock'];
          $product_price = $row['price'];
          $category_id = $row['category_title'];
          $brand_id = $row['brand_title'];

          if ($instock <= 2) {
            echo "<div class='col-md-4 mb-2'>
              <div class='card'>
                <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
                <div class='card-body'>
                  <h5 class='card-title'>$product_title</h5>
                  <p class='card-text'>$product_description <br> Only $instock pairs left</p>
                  <p class='card-text'>Price: Kshs.$product_price</p>
                  <a href='display_all.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                  <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
                </div>
              </div>
            </div>";
          }else {
            echo "<div class='col-md-4 mb-2'>
              <div class='card'>
                <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
                <div class='card-body'>
                  <h5 class='card-title'>$product_title</h5>
                  <p class='card-text'>$product_description</p>
                  <p class='card-text'>Price: Kshs.$product_price</p>
                  <a href='display_all.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                  <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
                </div>
              </div>
            </div>";
          }


        }
      }
    }

  }

  // get all products
  function get_all_products(){
    global $conn;

    // condition to check if category/ brand is present
    if (!isset($_GET['category'])) {
      if (!isset($_GET['brand'])) {
        $select_query = "SELECT * FROM `products`";
        $result = mysqli_query($conn, $select_query);
        while ($row = mysqli_fetch_assoc($result)) {
          $product_id = $row['product_id'];
          $product_title = $row['product_title'];
          $product_description = $row['product_description'];
          $product_image1 = $row['product_image1'];
          $instock = $row['instock'];
          $product_price = $row['price'];
          $category_id = $row['category_title'];
          $brand_id = $row['brand_title'];

          if ($instock <= 2) {
            echo "<div class='col-md-3 mb-2'>
              <div class='card'>
                <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
                <div class='card-body'>
                  <h5 class='card-title'>$product_title</h5>
                  <p class='card-text'>$product_description <br> Only $instock pairs left</p>
                  <p class='card-text'>Price: Kshs.$product_price</p>
                  <a href='index.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                  <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
                </div>
              </div>
            </div>";
          }else {
            echo "<div class='col-md-3 mb-2'>
              <div class='card'>
                <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
                <div class='card-body'>
                  <h5 class='card-title'>$product_title</h5>
                  <p class='card-text'>$product_description</p>
                  <p class='card-text'>Price: Kshs.$product_price</p>
                  <a href='index.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                  <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
                </div>
              </div>
            </div>";
          }

        }
      }
    }

  }

  // get unique categories
  function get_un_cat(){
    global $conn;

    // condition to check if category/ brand is present
    if (isset($_GET['category'])) {
      $category_id = $_GET['category'];
      $select_query = "SELECT * FROM `products` WHERE category_title=$category_id ORDER BY rand()";
      $result = mysqli_query($conn, $select_query);
      $num_of_rows = mysqli_num_rows($result);
      if ($num_of_rows == 0) {
        echo "<h2 class='text-center text-danger'>No Stock In this Category</h2>";
      }
      while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $product_title = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $product_price = $row['price'];
        $instock = $row['instock'];
        $category_id = $row['category_title'];
        $brand_id = $row['brand_title'];

        if ($instock <= 2) {
          echo "<div class='col-md-4 mb-2'>
            <div class='card'>
              <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
              <div class='card-body'>
                <h5 class='card-title'>$product_title</h5>
                <p class='card-text'>$product_description <br> Only $instock pairs left</p>
                <p class='card-text'>Price: Kshs.$product_price</p>
                <a href='display_all.php?category=$category_id&add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
              </div>
            </div>
          </div>";
        }else {
          echo "<div class='col-md-4 mb-2'>
            <div class='card'>
              <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
              <div class='card-body'>
                <h5 class='card-title'>$product_title</h5>
                <p class='card-text'>$product_description</p>
                <p class='card-text'>Price: Kshs.$product_price</p>
                <a href='display_all.php?category=$category_id&add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
              </div>
            </div>
          </div>";
        }

      }
    }

  }

  // get and display unique brands
  function get_un_brands(){
    global $conn;
    // condition to check if category/ brand is present
    if (isset($_GET['brand'])) {
      $brand_id = $_GET['brand'];
      $select_query = "SELECT * FROM `products` WHERE brand_title=$brand_id ORDER BY rand()";
      $result = mysqli_query($conn, $select_query);
      $num_of_rows = mysqli_num_rows($result);
      if ($num_of_rows == 0) {
        echo "<h2 class='text-center text-danger'>Sorry, No Stock For This Brand Yet</h2>";
      }
      while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $product_title = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $instock = $row['instock'];
        $product_price = $row['price'];
        $category_id = $row['category_title'];
        $brand_id = $row['brand_title'];

        if ($instock <= 2) {
          echo "<div class='col-md-4 mb-2'>
            <div class='card'>
              <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
              <div class='card-body'>
                <h5 class='card-title'>$product_title</h5>
                <p class='card-text'>$product_description <br> Only $instock pairs left</p>
                <p class='card-text'>Price: Kshs.$product_price</p>
                <a href='display_all.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
              </div>
            </div>
          </div>";
        }else {
          echo "<div class='col-md-4 mb-2'>
            <div class='card'>
              <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
              <div class='card-body'>
                <h5 class='card-title'>$product_title</h5>
                <p class='card-text'>$product_description</p>
                <p class='card-text'>Price: Kshs.$product_price</p>
                <a href='display_all.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
              </div>
            </div>
          </div>";
        }

      }
    }
  }

  // display brands
  function getBrands(){
    global $conn;
    $select_brands = "SELECT * FROM `brands`";
    $result = mysqli_query($conn, $select_brands);

    while ($row_data = mysqli_fetch_assoc($result)) {
      $brand_title = $row_data['brand_title'];
      $brand_id = $row_data['brand_id'];
      echo"<li class='nav-item'>
              <a href='display_all.php?brand=$brand_id' class='nav-link text-light dispBrand'>$brand_title</a>
            </li>";
    }
  }

  // display categories
  function getCategories(){
    global $conn;
    $select_categories = "SELECT * FROM `categories`";
    $result = mysqli_query($conn, $select_categories);

    while ($row_data = mysqli_fetch_assoc($result)) {
      $category_title = $row_data['category_title'];
      $category_id = $row_data['category_id'];
      echo"<li class='nav-item'>
              <a href='display_all.php?category=$category_id' class='nav-link text-light dispCat'>$category_title</a>
            </li>";
    }
  }

  // searching products function
  function search_product(){
    global $conn;

    if (isset($_GET['search_data_product'])) {
      $user_search_data =$_GET['search_data'];
      $search_query = "SELECT * FROM `products` WHERE product_keywords LIKE '%$user_search_data%'"; // Note: %val% when searching for a data, at whatever position the value is present, it will be displayed
      $result = mysqli_query($conn, $search_query);
      $num_of_rows = mysqli_num_rows($result);
      if ($num_of_rows == 0) {
        echo "<h2 class='text-center text-danger'>No result march</h2>";
      }
      while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $product_title = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $instock = $row['instock'];
        $product_price = $row['price'];
        $category_id = $row['category_title'];
        $brand_id = $row['brand_title'];

        if ($instock <= 2) {
          echo "<div class='col-md-4 mb-2'>
            <div class='card'>
              <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
              <div class='card-body'>
                <h5 class='card-title'>$product_title</h5>
                <p class='card-text'>$product_description <br> Only $instock pairs left</p>
                <p class='card-text'>Price: Kshs.$product_price</p>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
              </div>
            </div>
          </div>";
        }else {
          echo "<div class='col-md-4 mb-2'>
            <div class='card'>
              <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
              <div class='card-body'>
                <h5 class='card-title'>$product_title</h5>
                <p class='card-text'>$product_description</p>
                <p class='card-text'>Price: Kshs.$product_price</p>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View More</a>
              </div>
            </div>
          </div>";
        }

      }
    }

  }

  // view product details
  function view_product_details(){
    global $conn;

    // condition to check if category/ brand is present
    if (isset($_GET['product_id'])) {
      if (!isset($_GET['category'])) {
        if (!isset($_GET['brand'])) {
          $product_id = $_GET['product_id'];
          $select_query = "SELECT * FROM `products` WHERE product_id=$product_id";
          $result = mysqli_query($conn, $select_query);
          while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $product_title = $row['product_title'];
            $product_description = $row['product_description'];
            $product_image1 = $row['product_image1'];
            // $product_image2 = $row['product_image2'];
            // $product_image3 = $row['product_image3'];
            $product_price = $row['price'];
            $category_id = $row['category_title'];
            $brand_id = $row['brand_title'];

            echo "<div class='col-md-4 mb-2'>
              <div class='card'>
                <img src='./product_imgs/$product_image1' class='card-img-top pt-2' alt='$product_title'>
                <div class='card-body'>
                  <h5 class='card-title'>$product_title</h5>
                  <p class='card-text'>$product_description</p>
                  <p class='card-text'>Price: Kshs.$product_price</p>
                  <a href='index.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                  <a href='index.php' class='btn btn-secondary'>Go Home</a>
                </div>
              </div>
            </div>";
          }
        }
      }
    }


  }

  // get ip address
  function getIPAddress() {
    //whether ip is from the share internet
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
      }
    //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     }
     //whether ip is from the remote address
    else{
       $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }
  // $ip = getIPAddress();
  // echo 'User Real IP Address - '.$ip;

  // add to cart
  function add_to_cart(){
    if (isset($_GET['add_to_cart'])) {
      global $conn;

      $ip = getIPAddress();
      $get_product_id = $_GET['add_to_cart'];
      $select_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip' AND product_id=$get_product_id";
      $result = mysqli_query($conn, $select_query);
      $num_of_rows = mysqli_num_rows($result);
      if ($num_of_rows > 0) {
        // condition to check if product is already added to cart
        ?>
        <script>
          Swal.fire({
            position: "top",
            icon: "warning",
            title: "This product already exists in the cart!",
            showConfirmButton: false,
            timer: 1500
          })

          // window.open('index.php', '_self')
        </script>
        <?php
        if (count($_GET)>1) {
          unset($_GET['add_to_cart']);
          $query = http_build_query($_GET);
          $new_url = $_SERVER['PHP_SELF'].'?'.$query;
          header("Location: $new_url");
          exit;
        }
      }else {
        $insert_cart = "INSERT INTO
                          `cart_details` (product_id, ip_address, quantity)
                        VALUES ($get_product_id,'$ip',0)";
        $result = mysqli_query($conn, $insert_cart);
        ?>
        <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Product added to cart successfully!",
          showConfirmButton: false,
          timer: 1500
        }).then(() => {
          const load_page = window.location.href.split('?')[0];
          window.location.href = load_page;
        });
          // window.open('index.php', '_self')
        </script>
        <?php
        // echo "<script>window.open('index.php', '_self')</script>";
      }
    }
  }

  // cart item no. items function
  function cart_item_numbers(){
    if (isset($_GET['add_to_cart'])) {
      global $conn;

      $ip = getIPAddress();
      $select_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
      $result = mysqli_query($conn, $select_query);
      $count_cart_items = mysqli_num_rows($result);
    } else {
      global $conn;
      $ip = getIPAddress();
      $select_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
      $result = mysqli_query($conn, $select_query);
      $count_cart_items = mysqli_num_rows($result);
    }
    echo $count_cart_items;

  }

  // total cart price
  function total_cart_price(){
    global $conn;

    // get ip address for each user basically fetching total cart price for each user logged in
    $ip = getIPAddress();
    $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
    $result = mysqli_query($conn,$cart_query);
    $total_price = 0;
    $arr = [];

    while ($row = mysqli_fetch_array($result)) {
      $product_id = $row['product_id'];
      $quantity = $row['quantity'];
      $fetch_product_from_product_table = "SELECT * FROM `products` WHERE product_id=$product_id";
      $result_product = mysqli_query($conn,$fetch_product_from_product_table);
      while ($row_product = mysqli_fetch_array($result_product)){
        // $product_price = array($row_product['price']);
        // $product_values = array_sum($product_price);
        $price_table = $row_product['price'];
        $total_price = $quantity * $price_table;
        $arr[] += $total_price;
      }
    }
    echo number_format(array_sum($arr), 0, '.', ',');

  }

  // get user order details
  function user_oders(){
    global $conn;
    $username = $_SESSION['username'];

    $get_user_deatils = "SELECT * FROM `user_table` WHERE user_name='$username'";
    $result_query = mysqli_query($conn, $get_user_deatils);
    while ($row_query = mysqli_fetch_array($result_query)) {
      $user_id = $row_query['user_id'];
      if (!isset($_GET['edit_account']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account'])) {
        if (!isset($_GET['edit_account']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account'])) {
          if (!isset($_GET['edit_account']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account'])) {
            // then display total pending orders
            $get_total_user_orders = "SELECT * FROM `user_orders` WHERE user_id=$user_id AND 	order_status='pending'";
            $result_qty = mysqli_query($conn, $get_total_user_orders);
            $row_count = mysqli_num_rows($result_qty);
            if ($row_count == 1) {
              echo "<h3 class='text-center text-success'>You have <span class='text-danger'> $row_count </span>pending order</h3>
                    <p class='text-center'><a href='user_profile.php?my_orders' class='text-dark'> Order Details </a></p>";
            }elseif ($row_count > 0) {
              echo "<h3 class='text-center text-success'>You have <span class='text-danger'> $row_count </span>pending orders</h3>
                    <p class='text-center'><a href='user_profile.php?my_orders' class='text-dark'> Order Details </a></p>";
            }else {
              echo "<h3 class='text-center text-danger'>You have no pending orders</h3>
                    <p class='text-center'><a href='../index.php' class='text-dark'> Go Shopping </a></p>";
            }
          }
        }
      }

    }
  }

  function logAdminAction($conn, $admin_id, $action, $action_effect = null, $details = null) {
    // Prepare the query to insert the log
    $stmt = $conn->prepare("INSERT INTO admin_logs (adm_user, action, details, action_effect, timestamp) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $admin_id, $action, $details, $action_effect); // Removed the 'NOW()' parameter

    // Execute and check for errors
    if ($stmt->execute()) {
        return true; // Log added successfully
    } else {
        error_log("Failed to log admin action: " . $stmt->error); // Log error for debugging
        return false;
    }
  }



 ?>
