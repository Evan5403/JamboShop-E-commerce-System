<?php
  session_start();
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  if (!isset($_SESSION['username'])) {
    echo "<script>window.open('user_login.php','_self');</script>";
    exit();
  }
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $get_user_query = "SELECT * FROM user_table WHERE user_name='$username'";
    $result = mysqli_query($conn, $get_user_query);
    $run_query = mysqli_fetch_array($result);
    $user_id = $run_query['user_id'];
  }
  $get_ip = getIPAddress();
  $subtotal_price = 0;
  $arr = [];
  $arr_qty = [];
  $total_qty = 0;
  $cart_query = "SELECT * FROM cart_details WHERE ip_address='$get_ip'";
  $result_cart_price = mysqli_query($conn, $cart_query);
  $count = mysqli_num_rows($result_cart_price);
  if ($count == 0) {
    echo "<script>window.open('../index.php#featuredProducts','_self')</script>";
    exit();
  }
  while ($row_price = mysqli_fetch_array($result_cart_price)) {
    $product_id = $row_price['product_id'];
    // get products
    $select_products = "SELECT
                          p.product_id,
                          p.product_title,
                          p.product_image1,
                          p.instock,
                          COALESCE(
                            (p.price - (p.price * (fs.discount_value / 100))),
                            pp.display_price
                          ) AS final_price,
                          pp.promotion_id,
                          pp.original_price
                        FROM
                          products p
                        LEFT JOIN
                          product_promotions pp
                        ON
                          p.product_id = pp.product_id
                        LEFT JOIN
                          (
                            SELECT
                              applicable_id AS product_id,
                              discount_value
                            FROM
                              flash_sales
                            WHERE
                              status = 'active'
                          ) fs
                        ON
                            p.product_id = fs.product_id
                        WHERE
                          p.product_id = '$product_id'";
    $execute_query = mysqli_query($conn, $select_products);
    $row_product_price = mysqli_fetch_assoc($execute_query);
    $product_price = (int) $row_product_price['final_price'];
    $fetch_qty = "SELECT * FROM cart_details WHERE product_id='$product_id'";
    $execute_query = mysqli_query($conn, $fetch_qty);
    $row_price = mysqli_fetch_array($execute_query);
    $qty = (int) $row_price['quantity'];
    $arr_qty[] += $qty;
    $total_qty = array_sum($arr_qty);
    $calculation = $product_price * $qty;

    $arr[] += $calculation;
    $subtotal_price = array_sum($arr);
  }
  // Fetch active cart promotions
  $query = "SELECT discount_value, minimum_cart_value
          FROM promotions
          WHERE applicable_to = 'cart' AND status = 'active'
          ORDER BY minimum_cart_value ASC";
  $result = mysqli_query($conn, $query);

  $discount = 0;
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($subtotal_price >= $row['minimum_cart_value']) {
            $discount = ($subtotal_price * $row['discount_value']) / 100;
        }
    }
  }

  // Calculate total
  $total_value = round($subtotal_price - $discount);

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <div class="site page-cart" id="page">

      <aside class="site-off desktop-hide">
        <div class="off-canvas">
          <div class="canvas-head flexitem">
            <div class="logo"><a href="#"><span class="circle"></span>.JamboShop</a></div>
            <a href="#" class="t-close flexcenter"><i class="ri-close-line"></i></a>
          </div>
          <div class="departments"></div>
          <nav></nav>
          <div class="thetop-nav"></div>
        </div>
      </aside>

      <!-- HEADER -->
      <header id="stickyHeader">
        <?php
        if (isset($_SESSION['username'])) {
          $username = $_SESSION['username'];
          $get_userID = "SELECT * FROM user_table WHERE user_name='$username'";
          $resultID = mysqli_query($conn, $get_userID);
          $row_userID = mysqli_fetch_assoc($resultID);
          $userID = $row_userID['user_id'];
        }

         ?>
        <!-- header-top -->
        <div class="header-top mobile-hide">
          <div class="container">
            <div class="wrapper flexitem">
              <div class="left">
                <ul class="flexitem main-links">
                  <li><a href="#">Blog</a></li>
                  <li><a href="../index.php#featuredProducts">Featured Products</a></li>
                  <li><a href="user_dashboard/user_profile.php?my_wishlist">Wishlist</a></li>
                </ul>
              </div>
              <div class="right">
                <ul class="flexitem main-links">
                  <?php
                    if (isset($_SESSION['username'])){?>
                      <li><a href="user_dashboard/user_profile.php">My Account</a></li>
                      <li><a href="user_logout.php">Logout</a></li>
                   <?php } else {?>
                     <li><a href="user_login.php">Signup/Login</a></li>
                   <?php } ?>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Header Nav-->
        <div class="header-nav">
          <div class="container">
            <div class="wrapper flexitem">
              <a href="#" class="trigger desktop-hide"><span class="i ri-menu-2-line"></span></a>
              <div class="left flexitem">
                <div class="logo"><a href="#"><span class="circle"></span>.JamboShop</a></div>
                <nav class="mobile-hide">
                  <ul class="flexitem second-links">
                    <li><a href="index.php">Home</a></li>
                    <!-- <li><a href="#">Shop</a></li> -->
                    <li class="has-child">
                      <a href="#">Gender & Brands
                        <div class="icon-small"><i class="ri-arrow-down-s-line"></i>  </div>
                      </a>
                      <div class="mega">
                        <div class="container">
                          <div class="wrapper">
                            <div class="flexcol">
                              <div class="row">
                                <h4>Men</h4>
                                <ul>
                                  <?php
                                    $get_male_products = "SELECT
                                                            p.product_id,
                                                            p.demographic,
                                                            p.category_title AS category_id,
                                                            c.category_title
                                                          FROM
                                                            products p
                                                          INNER JOIN
                                                            categories c
                                                          ON
                                                              p.category_title = c.category_id
                                                          WHERE demographic='men'
                                                          GROUP BY c.category_title";
                                    $result_products = mysqli_query($conn,$get_male_products);
                                    while ($row_product = mysqli_fetch_assoc($result_products)) {
                                      ?>
                                      <li><a href="../products.php?category=<?php echo $row_product['category_id'] ?>">
                                        <?php echo $row_product['category_title'] ?>
                                      </a></li>
                                  <?php  } ?>
                                </ul>
                              </div>
                            </div>
                            <div class="flexcol">
                              <div class="row">
                                <h4>Women</h4>
                                <ul>
                                  <?php
                                    $get_women_products = "SELECT
                                                            p.product_id,
                                                            p.demographic,
                                                            p.category_title AS category_id,
                                                            c.category_title
                                                          FROM
                                                            products p
                                                          INNER JOIN
                                                            categories c
                                                          ON
                                                              p.category_title = c.category_id
                                                          WHERE demographic='women'
                                                          GROUP BY c.category_title";
                                    $result_products = mysqli_query($conn,$get_women_products);
                                    while ($row_product = mysqli_fetch_assoc($result_products)) {
                                      ?>
                                      <li><a href="../products.php?category=<?php echo $row_product['category_id'] ?>">
                                        <?php echo $row_product['category_title'] ?>
                                      </a></li>
                                  <?php  } ?>
                                </ul>
                              </div>
                            </div>
                            <div class="flexcol">
                              <div class="row">
                                <h4>Kids</h4>
                                <ul>
                                  <?php
                                    $get_kids_products = "SELECT
                                                            p.product_id,
                                                            p.demographic,
                                                            p.category_title AS category_id,
                                                            c.category_title
                                                          FROM
                                                            products p
                                                          INNER JOIN
                                                            categories c
                                                          ON
                                                              p.category_title = c.category_id
                                                          WHERE demographic='kids'
                                                          GROUP BY c.category_title";
                                    $result_products = mysqli_query($conn,$get_kids_products);
                                    while ($row_product = mysqli_fetch_assoc($result_products)) {
                                      ?>
                                      <li><a href="../products.php?category=<?php echo $row_product['category_id'] ?>">
                                        <?php echo $row_product['category_title'] ?>
                                      </a></li>
                                  <?php  } ?>
                                </ul>
                              </div>
                            </div>
                            <div class="flexcol">
                              <div class="row">
                                <h4>Unisex</h4>
                                <ul>
                                  <?php
                                    $get_kids_products = "SELECT
                                                            p.product_id,
                                                            p.demographic,
                                                            p.category_title AS category_id,
                                                            c.category_title
                                                          FROM
                                                            products p
                                                          INNER JOIN
                                                            categories c
                                                          ON
                                                              p.category_title = c.category_id
                                                          WHERE demographic='unisex'
                                                          GROUP BY c.category_title";
                                    $result_products = mysqli_query($conn,$get_kids_products);
                                    while ($row_product = mysqli_fetch_assoc($result_products)) {
                                      ?>
                                      <li><a href="../products.php?category=<?php echo $row_product['category_id'] ?>">
                                        <?php echo $row_product['category_title'] ?>
                                      </a></li>
                                  <?php  } ?>
                                </ul>
                              </div>
                            </div>
                            <div class="flexcol">
                              <div class="row">
                                <h4>Top Brands</h4>
                                <ul class="brands">
                                  <?php
                                    // Fetch all departments
                                    $select_brands = "SELECT * FROM `brands`";
                                    $result_brands = mysqli_query($conn, $select_brands);

                                    while ($row = mysqli_fetch_assoc($result_brands)) {
                                        $brand_id = $row['brand_id'];
                                        $brand_title = $row['brand_title'];
                                  ?>
                                    <li><a href="products.php?brand=<?php echo $brand_id ?>"><?php echo $brand_title ?></a></li>
                                  <?php } ?>
                                </ul>
                                <!-- <a href="#" class="view-all">View all brands <i class="ri-arrow-right-line"></i> </a> -->
                              </div>
                            </div>
                            <div class="flexcol products">
                              <div class="row">
                                <?php
                                  $most_sold_product = "SELECT * FROM products WHERE instock_sold = (SELECT MAX(instock_sold) FROM products)";
                                  $result_product = mysqli_query($conn, $most_sold_product);
                                  if ($result_product) {
                                    $row_product = mysqli_fetch_assoc($result_product); ?>
                                    <div class="media">
                                      <div class="thumbnail object-cover">
                                        <a href="#"><img src="../product_imgs/<?php echo $row_product['product_image1'] ?>" alt=""></a>
                                      </div>
                                    </div>
                                   <div class="text-content">
                                     <h4>Most Wanted!</h4>
                                     <a href="#" class="primary-button">Order Now</a>
                                   </div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <!-- <li><a href="#">Men</a></li> -->
                    <li>
                      <?php
                        $get_recent_cat = "SELECT * FROM categories ORDER BY category_id DESC LIMIT 1";
                        $result_cat = mysqli_query($conn, $get_recent_cat);
                        if ($result_cat) {
                          $row_cat = mysqli_fetch_assoc($result_cat);
                          $category_id = $row_cat['category_id'];
                          $category_title = $row_cat['category_title'];
                           ?>
                          <a href="../products.php?category=<?php echo $category_id ?>"><?php echo $category_title ?>
                            <div class="fly-item"><span>New!</span></div>
                          </a>
                      <?php } ?>
                    </li>
                  </ul>
                </nav>
              </div>
              <div class="right">
                <ul class="flexitem second-links">
                  <?php
                    $count_wishlisted_items = '';
                    if (isset($_SESSION['username'])) {
                      // Fetch all products in wishlist
                      $get_wishlist = "SELECT * FROM `wishlist` WHERE user_id='$userID'";
                      $result_wishlist = mysqli_query($conn,$get_wishlist);
                      $count_wishlisted_items = mysqli_num_rows($result_wishlist);
                    }

                   ?>
                  <li class="mobile-hide wishlist">
                    <a href="javascript:void(0);">
                      <div class="icon-large"><i class="ri-heart-line"></i></div>
                      <?php
                        if ($count_wishlisted_items) { ?>
                          <div class="fly-item"><span class="item-number"><?php echo $count_wishlisted_items ?></span></div>
                      <?php } ?>

                    </a>

                    <div class="mini-wishlist">
                      <div class="content">
                        <div class="cart-head">
                          <?php echo ($count_wishlisted_items) ? $count_wishlisted_items . 'item(s) in wishlist' : ''; ?>
                        </div>
                        <div class="cart-body">
                          <ul class="products mini">
                            <?php
                            if (isset($_SESSION['username'])) {
                              if ($count_wishlisted_items == 0) {
                                echo "<h1>No item in wishlist</h1>";
                              }else {
                                while ($row = mysqli_fetch_array($result_wishlist)) {
                                  $user_id = $row['user_id'];
                                  $product_id = $row['product_id'];
                                  $fetch_product_details = "SELECT
                                                              w.user_id,
                                                              w.product_id,
                                                              p.product_title,
                                                              p.product_image1,
                                                              p.price,
                                                              p.status,
                                                              COALESCE(
                                                                (p.price - (p.price * (fs.discount_value / 100))),
                                                                pp.display_price
                                                              ) AS final_price,
                                                              COALESCE(
                                                                fs.discount_value, -- Use flash sale price if active
                                                                pp.discount_value -- Else use promotion price
                                                              ) AS final_discount_value,
                                                              pp.promotion_id,
                                                              pp.original_price
                                                            FROM
                                                              wishlist w
                                                            INNER JOIN
                                                              products p
                                                            ON
                                                              w.product_id = p.product_id
                                                            LEFT JOIN
                                                              product_promotions pp
                                                            ON
                                                              p.product_id = pp.product_id
                                                            LEFT JOIN
                                                              (
                                                                SELECT
                                                                  applicable_id AS product_id,
                                                                  discount_value
                                                                FROM
                                                                  flash_sales
                                                                WHERE
                                                                  status = 'active'
                                                              ) fs
                                                            ON
                                                                p.product_id = fs.product_id
                                                            WHERE
                                                              w.product_id = '$product_id'";
                                  $result_product = mysqli_query($conn,$fetch_product_details);
                                  $row_product = mysqli_fetch_array($result_product);
                                  $product_title = $row_product['product_title'];
                                  $product_image1 = $row_product['product_image1'];
                                  $final_price = $row_product['final_price'];
                                  ?>
                                  <li class="item wishlist-item-removed">
                                    <div class="thumbnail object-cover">
                                      <a href="javascript:void(0);">
                                        <img src="../product_imgs/<?php echo $product_image1 ?>" alt="">
                                      </a>
                                    </div>
                                    <div class="item-content">
                                      <p><a href="../product-details.php?product=<?php echo $product_id ?>"><?php echo $product_title ?></a></p>
                                      <span class="price">
                                        <span>Kshs.<?php echo floor($final_price) ?></span>
                                      </span>
                                    </div>
                                    <a href="javascript:void(0);"
                                       class="remove-wishlist"
                                       data-product-id="<?php echo $product_id; ?>">
                                      <i class="ri-close-line"></i>
                                    </a>
                                  </li>

                                <?php }}}else {
                                  echo "Login/Signup to manage your wishlist";
                                }?>
                          </ul>
                        </div>
                      </div>
                    </div>

                  </li>
                  <li class="iscart">
                    <a href="javascript:void(0);">
                      <div class="icon-large">
                        <i class="ri-shopping-cart-line"></i>
                        <div class="fly-item"><span class="item-number"><?php cart_item_numbers(); ?></span></div>
                      </div>
                      <div class="icon-text">
                        <div class="min-text">Total</div>
                        <div class="cart-total">Ksh.<?php total_cart_price(); ?></div>
                      </div>
                    </a>

                    <div class="mini-cart">
                      <div class="content">
                        <div class="cart-head">
                          <?php cart_item_numbers(); ?> item(s) in cart
                        </div>
                        <div class="cart-body">
                          <ul class="products mini">
                            <?php
                              // Fetch all products in cart
                              $ip = getIPAddress();
                              $cart_details = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
                              $result_cart = mysqli_query($conn,$cart_details);
                              $count_cart_items = mysqli_num_rows($result_cart);
                              $total_cart_price = 0;
                              $arr = [];

                              if ($count_cart_items == 0) {
                                echo "<h1>No item in cart</h1>";
                              }else {
                                while ($row = mysqli_fetch_array($result_cart)) {
                                  $product_id = $row['product_id'];
                                  $cart_qty = $row['quantity'];
                                  $fetch_product_from_product_table = "SELECT
                                                                        p.product_id,
                                                                        p.product_title,
                                                                        p.product_image1,
                                                                        p.instock,
                                                                        COALESCE(
                                                                          (p.price - (p.price * (fs.discount_value / 100))),
                                                                          pp.display_price
                                                                        ) AS final_price,
                                                                        pp.promotion_id,
                                                                        pp.original_price
                                                                      FROM
                                                                        products p
                                                                      LEFT JOIN
                                                                        product_promotions pp
                                                                      ON
                                                                        p.product_id = pp.product_id
                                                                      LEFT JOIN
                                                                        (
                                                                          SELECT
                                                                            applicable_id AS product_id,
                                                                            discount_value
                                                                          FROM
                                                                            flash_sales
                                                                          WHERE
                                                                            status = 'active'
                                                                        ) fs
                                                                      ON
                                                                          p.product_id = fs.product_id
                                                                      WHERE
                                                                        p.product_id = '$product_id'";
                                  $result_product = mysqli_query($conn,$fetch_product_from_product_table);
                                  while ($row_product = mysqli_fetch_array($result_product)){
                                    $product_title = $row_product['product_title'];
                                    $product_image1 = $row_product['product_image1'];
                                    $promotion_id = $row_product['promotion_id'];
                                    $price_table = (int) $row_product['final_price'];
                                    $instock = (int) $row_product['instock'];
                                    $total_cost_price = floor($cart_qty * $row_product['final_price']);
                                    $arr[] = $total_cost_price;
                                  ?>
                                  <li class="item item-to-be-removed">
                                    <div class="thumbnail object-cover">
                                      <a href="javascript:void(0);"><img src="../product_imgs/<?php echo $product_image1?>" alt=""></a>
                                    </div>
                                    <div class="item-content">
                                      <p><a href="javascript:void(0);"><?php echo $product_title ?></a></p>
                                      <span class="price">
                                        <span>Kshs.<?php echo floor($price_table) ?></span>
                                        <span class="fly-item"><span><?php echo $cart_qty ?>X</span></span>
                                      </span>
                                    </div>
                                    <a href="javascript:void(0);" class="item-remove" data-product-id="<?php echo $product_id; ?>">
                                      <i class="ri-close-line"></i>
                                    </a>
                                  </li>

                                <?php }}
                                  $total_cart_price = array_sum($arr);
                                ?>

                          </ul>
                        </div>
                        <div class="cart-footer">
                          <div class="subtotal">
                            <p>Subtotal</p>
                            <p><strong>Kshs.<?php  echo $total_cart_price ?></strong></p>
                          </div>
                          <div class="actions">
                            <a href="checkout.php" class="primary-button">Checkout</a>
                            <a href="../cart.php" class="secondary-button">View Cart</a>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                    </div>

                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="header-main mobile-hide">
          <div class="container">
            <div class="wrapper flexitem">
              <div class="left">
                <div class="dpt-cat">
                  <div class="dpt-head">
                    <div class="main-text">All Departments</div>
                    <?php
                      $get_total_products = "SELECT COUNT(product_id) AS total_products FROM products";
                      $execute_query = mysqli_query($conn, $get_total_products);
                      $row = mysqli_fetch_assoc($execute_query);
                      $total_products =  $row['total_products'];
                     ?>
                    <div class="mini-text mobile-hide">Total <?php echo $total_products ?> products</div>
                    <a href="javascript:void(0);" class="dpt-trigger mobile-hide">
                      <i class="ri-menu-3-line ri-xl"></i>
                      <i class="ri-close-line ri-xl"></i>
                    </a>
                  </div>
                  <div class="dpt-menu">
                    <ul class="second-links">
                      <?php
                        // Fetch all departments
                        $select_department = "SELECT * FROM `department`";
                        $result_dpt = mysqli_query($conn, $select_department);

                        while ($row = mysqli_fetch_assoc($result_dpt)) {
                            $department_id = $row['department_id'];
                            $department_title = $row['department_title'];

                            // Fetch categories for the current department
                            $select_categories = "SELECT * FROM `categories` WHERE `department_id` = $department_id";
                            $result_cat = mysqli_query($conn, $select_categories);

                            echo "<li class='has-child'>
                                    <a href='javascript:void(0);'>
                                      $department_title
                                      <div class='icon-small'> <i class='ri-arrow-right-s-line'></i> </div>
                                    </a>
                                    <ul>";

                            // Display categories under the current department
                            if (mysqli_num_rows($result_cat) > 0) {
                                while ($cat_row = mysqli_fetch_assoc($result_cat)) {
                                    $category_id  = $cat_row['category_id'];
                                    $category_title = $cat_row['category_title'];
                                    echo "<li><a href='products.php?category=$category_id'>$category_title</a></li>";
                                }
                            } else {
                                echo "<li><a href='javascript:void(0);'>No Categories Available</a></li>";
                            }

                            echo "</ul>
                                </li>";
                        }
                        ?>

                    </ul>
                  </div>
                </div>
              </div>
              <div class="right">
                <div class="search-box">
                  <form class="search" id="siteSearch" method="get">
                    <span class="icon-large"><i class="ri-search-line"></i></span>
                    <input type="search" name="query" placeholder="Search Products" autocomplete="off">
                    <button type="submit" name="button">Search</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- MARGIN -->
      <main>

        <div class="single-checkout">
          <div class="container">
            <div class="wrapper">
              <div class="breadcrumb">
                <ul class="flexitem">
                  <li><a href="../index.php">Home</a></li>
                  <li><a href="../cart.php">cart</a></li>
                  <li>checkout</li>
                </ul>
              </div>
              <div class="checkout flexwrap">
                <div class="item left styled">
                  <form action="" method="post" id="checkoutForm">
                    <h1>Contact Info</h1>
                    <p>
                      <input type="hidden" name="amount" id="hidden-amount-due" value="" readonly>
                      <input type="hidden" name="expected_date" id="hidden-expected-date" value="" readonly>
                      <label for="contact_number">Contact Phone Number <span></span></label> <br>
                      <input
                       type="tel"
                       name="contact_number"
                       placeholder="Enter Contact Phone Number"
                       id="contact_number"
                       pattern="[0-9]{10}"
                       title="Please enter correct phone number"
                       required>
                    </p>
                    <p>
                      <label for="contact_email">Contact Email Address <span></span></label><br>
                      <input type="email" name="contact_email" placeholder="Enter Contact Email Address" id="contact_email" required>
                    </p>
                    <h1>Delivery Address</h1>
                    <p>
                      <label for="county">Select County</label><br>
                      <select name="county" id="county" onchange="loadConstituencies(); updateAddress();" required>
                        <option value="">Select County</option>
                      </select>
                    </p>
                    <p>
                      <label for="constituency">Select Constituency</label><br>
                      <select name="constituency" id="constituency" onchange="calculateDeliveryCost(); updateAddress(); calculateExpectedDate();" required>
                        <option value="">Select Constituency</option>
                      </select>
                    </p>
                    <p>
                      <label for="address-input">Address <span></span></label><br>
                      <input type="text" name="address" id="address-input" placeholder="Delivery Address" value="" readonly required>
                    </p>
                    <h1>Payment Mode</h1>
                    <p>
                      <label>Payment Mode <span></span></label><br>
                      <select name="constituency" name="payment_mode" id="dropdown" onchange="toggleInput()" required>
                        <option value="">Select Payment Mode</option>
                        <option value="pay_on_delivery">Pay On Delivery </option>
                        <option value="pay_with_mpesa">Pay With Mpesa</option>
                        <!-- <option value="pay_with_paypal">Pay With PayPal</option> -->
                      </select>
                    </p>
                    <p id="inputContainer" style="display: none;">
                      <label for="textInput">Enter Mpesa Number <span></span></label><br>
                      <input
                        type="tel"
                        name="mpesa_number"
                        id="textInput"
                        placeholder="Enter Your Mpesa Number"
                        value="254">
                    </p>

                    <div class="primary-checkout" id="submitContainer">
                     <input type="submit" class="primary-button" name="submit_order" value="Place Order" id="submitOrderBtn">
                    </div>
                  </form>
                </div>
                <div class="item right">
                  <h2>Order Summary</h2>
                  <div class="summary-order is_sticky">
                    <div class="summary-totals">
                      <ul>
                        <li>
                          <span>Subtotal</span>
                          <span>Kshs. <span id="subtotal"><?php echo $total_value; ?></span> </span>
                        </li>
                        <li>
                          <span>Delivery Fee</span>
                          <span>Kshs. <span id="delivery-cost"></span> </span>
                        </li>
                        <li>
                          <span>Expected Date</span>
                          <span><span id="expected-date"></span> </span>
                        </li>
                        <li>
                          <span>Total</span>
                          <strong>Ksh. <span id="amount-due"> <?php echo $total_value; ?></span> </strong>
                        </li>
                      </ul>
                    </div>
                    <ul class="products mini">
                      <?php
                        $fetch_cart_details = "SELECT * FROM cart_details WHERE ip_address='$get_ip'";
                        $result_cart_details = mysqli_query($conn, $fetch_cart_details);
                        while ($row_cart_details = mysqli_fetch_array($result_cart_details)) {
                          $product_id = $row_cart_details['product_id'];
                          $cart_qty = $row_cart_details['quantity'];
                          // get products
                          $select_products = "SELECT
                                                p.product_id,
                                                p.product_title,
                                                p.product_image1,
                                                p.instock,
                                                COALESCE(
                                                  (p.price - (p.price * (fs.discount_value / 100))),
                                                  pp.display_price
                                                ) AS final_price,
                                                pp.promotion_id,
                                                pp.original_price
                                              FROM
                                                products p
                                              LEFT JOIN
                                                product_promotions pp
                                              ON
                                                p.product_id = pp.product_id
                                              LEFT JOIN
                                                (
                                                  SELECT
                                                    applicable_id AS product_id,
                                                    discount_value
                                                  FROM
                                                    flash_sales
                                                  WHERE
                                                    status = 'active'
                                                ) fs
                                              ON
                                                  p.product_id = fs.product_id
                                              WHERE
                                                p.product_id = '$product_id'";
                          $execute_query = mysqli_query($conn, $select_products);
                          $row_product_details = mysqli_fetch_assoc($execute_query);
                          $product_img = $row_product_details['product_image1'];
                          $product_title = $row_product_details['product_title'];
                          $subproduct_price = (int) $row_product_details['final_price'];
                          $product_price = $subproduct_price * $cart_qty;
                          ?>
                          <li class="item">
                            <div class="thumbnail object-cover">
                              <img src="../product_imgs/<?php echo $product_img ?>" alt="">
                            </div>
                            <div class="item-content">
                              <p><?php echo $product_title ?></p>
                              <span class="price">
                                <span>Kshs. <?php echo $product_price ?></span>
                                <span>x <?php echo $cart_qty ?></span>
                              </span>
                            </div>
                          </li>
                        <?php }?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
      <footer>
        <!-- newsletter -->
        <!-- <div class="newsletter">
          <div class="container">
            <div class="wrapper">
              <div class="box">
                <div class="content">
                  <h3>Join Our Newsletter</h3>
                  <p>Get E-mail updates about our latest shop and <strong>special offers</strong></p>
                </div>
                <form action="" class="search">
                  <span class="icon-large"><i class="ri-mail-line"></i></span>
                  <input type="mail" placeholder="Your Email Address" required>
                  <button type="submit">Sign Up</button>
                </form>
              </div>
            </div>
          </div>
        </div> -->

        <div class="widgets">
          <div class="container">
            <div class="wrapper">
              <div class="flexwrap">
                <div class="row">
                  <div class="item mini-links">
                    <h4>Customer Support</h4>
                    <ul class="flexcol">
                      <li><a href="">Contact Us</a></li>
                      <li><a href="">Order Tracking</a></li>
                      <li><a href="">Returns & Refund Policy</a></li>
                      <li><a href="">FAQS</a></li>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <div class="item mini-links">
                    <h4>Product Categories</h4>
                    <ul class="flexcol">
                      <?php
                        $product_cat = "SELECT
                                            vod.invoice_number,
                                            uo.invoice_number,
                                            uo.order_status,
                                            c.category_title,
                                            c.category_id,
                                            SUM(vod.quantity) AS total_purchases
                                          FROM
                                            view_order_details vod
                                          INNER JOIN
                                            products p ON vod.product_id = p.product_id
                                          INNER JOIN
                                            categories c ON p.category_title = c.category_id
                                          INNER JOIN
                                            user_orders uo ON vod.invoice_number = uo.invoice_number
                                          WHERE
                                            uo.order_status = 'complete'
                                          GROUP BY
                                            c.category_title
                                          ORDER BY
                                            total_purchases DESC";
                        $execution_query = mysqli_query($conn, $product_cat);
                        while ($row = mysqli_fetch_assoc($execution_query)) {
                          $categoryTitle = $row['category_title'];
                          $categoryId = $row['category_id'];
                          ?>
                          <li><a href="products.php?category=<?php echo $categoryId ?>"><?php echo $categoryTitle ?></a></li>
                        <?php } ?>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <div class="item mini-links">
                    <h4>Flash Sales & Promotions</h4>
                    <ul class="flexcol">
                      <?php
                        $flashsale = "SELECT
                                        flash_sales.*
                                      FROM
                                        flash_sales
                                      ORDER BY
                                        flash_sales.status = 'active' DESC,
                                        flash_sales.end_date DESC
                                      LIMIT 1";
                        $result_flashsale = mysqli_query($conn, $flashsale);
                        if ($row_flashsale = mysqli_fetch_assoc($result_flashsale)){
                          $flashsale = $row_flashsale['flash_sale_name'];
                          ?>
                          <li><a href="index.php#trending"><?php echo $flashsale ?></a></li>
                        <?php }
                        $promotions = "SELECT
                                        promotions.*
                                      FROM
                                        promotions
                                      WHERE
                                        promotions.status = 'active'
                                      ";
                        $result_promotions = mysqli_query($conn, $promotions);
                        while ($row = mysqli_fetch_assoc($result_promotions)){
                          $promotionName = $row['promotion_name'];
                          ?>
                          <li><a href="index.php#trending"><?php echo $promotionName ?></a></li>
                        <?php } ?>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <div class="item mini-links">
                    <h4>About Us</h4>
                    <ul class="flexcol">
                      <li><a href="">About Jambo Shop</a></li>
                      <li><a href="">Eco-Friendly Fashion Mission</a></li>
                      <li><a href="">Careers </a></li>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <div class="item mini-links">
                    <h4>Payment & Security</h4>
                    <ul class="flexcol">
                      <li><a href="">Payment Methods</a></li>
                      <li><a href="">Privacy Policy</a></li>
                      <li><a href=""> Terms & Conditions </a></li>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <div class="item mini-links">
                    <h4>Copyright & Legal Notice</h4>
                    <ul class="flexcol">
                      <li><a href="">© 2025 Jambo Shop. All Rights Reserved</a></li>
                      <li><a href="">Designed & Developed by [Your Name/Company]</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- footer info -->
         <div class="footer-info">
          <div class="container">
            <div class="wrapper">
              <div class="flexcol">
                <div class="logo">
                  <a href=""><span class="circle"></span>.Store</a>
                </div>
                <div class="socials">
                  <ul class="flexitem">
                    <li><a href="#"><i class="ri-twitter-line"></i></a></li>
                    <li><a href="#"><i class="ri-facebook-line"></i></a></li>
                    <li><a href=""><i class="ri-instagram-line"></i></a></li>
                    <li><a href=""><i class="ri-linkedin-line"></i></a></li>
                    <li><a href=""><i class="ri-youtube-line"></i></a></li>
                  </ul>
                </div>
              </div>
              <p class="mini-text">Copyright 2022 &#169; .Store. All rights reserved </p>
            </div>
          </div>
         </div>

      </footer>

      <!-- menu button -->
      <div class="menu-bottom desktop-hide">
        <div class="container">
          <div class="wrapper">
            <nav>
              <ul class="flexitem">
                <li>
                  <a href="#">
                    <i class="ri-bar-chart-line"></i>
                    <span>Featured Products</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="ri-user-6-line"></i>
                    <span>Account</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="ri-heart-line"></i>
                    <span>Wishlist</span>
                  </a>
                </li>
                <li>
                  <a href="#0" class="t-search">
                    <i class="ri-search-line"></i>
                    <span>Search</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:void(0);" class="cart-trigger">
                    <i class="ri-shopping-cart-line"></i>
                    <span>Cart</span>
                    <div class="fly-item">
                      <span class="item-number"><?php cart_item_numbers(); ?></span>
                    </div>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>

      <!-- search-bottom -->
      <div class="search-bottom desktop-hide">
        <div class="container">
          <div class="wrapper">
            <form action="" class="search">
              <a href="#" class="t-close search-close flexcenter"><i class="ri-close-line"></i></a>
              <span class="icon-large"><i class="ri-search-line"></i></span>
              <input type="search" placeholder="Search Products" required>
              <button type="submit">Search</button>
            </form>
          </div>
        </div>
      </div>


      <!-- overlay -->
      <div class="overlay">

      </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.3.1/index.js"></script>
    <script src="../script/script.js" charset="utf-8"></script>

    <script type="text/javascript">

      window.onload = function(){
        loadCounties();
      }

      // payment mode dropdown menu
      function toggleInput() {
        var dropdown = document.getElementById("dropdown");
        var inputContainer = document.getElementById("inputContainer");
        var submitContainer = document.getElementById("submitContainer");
        // var paypalButtonContainer = document.getElementById("paypal-button-container");

        if (dropdown.value === "pay_with_mpesa") {
            inputContainer.style.display = "block";
            submitContainer.innerHTML = '<input type="submit" class="primary-button" name="confirm_payment" value="Confirm Payment" id="confirmPaymentBtn">';
            // paypalButtonContainer.style.display = "none";
        }
        if (dropdown.value === "pay_on_delivery"){
            inputContainer.style.display = "none";
            // paypalButtonContainer.style.display = "none";
            submitContainer.innerHTML = '<input type="submit" class="primary-button" name="submit_order" value="Place Order" id="submitOrderBtn">';
        }
      }

      /* -------
        GET COUNTIES
      ---------------*/
      function loadCounties() {
       var xhr = new XMLHttpRequest();
       xhr.open('GET', 'location_api/counties.php', true);
       xhr.onreadystatechange = function () {
           if (xhr.readyState == 4 && xhr.status == 200) {
               document.getElementById('county').innerHTML += xhr.responseText;
           }
       };
       xhr.send();
      }

      /* -------
        GET CONSTITUTENCIES
      ---------------*/
      function loadConstituencies() {
       var county = document.getElementById('county').value;

       var xhr = new XMLHttpRequest();
       xhr.open('POST', 'location_api/get_constituencies.php', true);
       xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
       xhr.onreadystatechange = function () {
           if (xhr.readyState == 4 && xhr.status == 200) {
               document.getElementById('constituency').innerHTML = xhr.responseText;
           }
       };
       xhr.send('county=' + county);
      }

      /* -------
        UPDATE ADDRESS
      ---------------*/
      function updateAddress(){
        var county = document.getElementById('county').value;
        var constituency = document.getElementById('constituency').value;
        // console.log(county,constituency);

        if (county && constituency) {
          var address = constituency + ', ' + county;
          // document.getElementById('address-input').value = address;
          var addressInput = document.getElementById('address-input');
          addressInput.value = address;
          // address.style.display = 'none';
          address.offsetHeight;
          addressInput.style.display = '';
          console.log(document.getElementById('address-input').value);
        }
        // document.getElementById('address-input').value = '';
      }

      /* ---------------------
        GET COST FOR EVERY CONSTITUTENCY
      ---------------------------------*/
      function calculateDeliveryCost() {
        var constituency = document.getElementById('constituency').value;
        var county = document.getElementById('county').value;

        if (constituency === "") {
           document.getElementById('delivery-cost').innerHTML = '';
           return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'location_api/calculate_delivery_cost.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
           var deliveryCost = parseFloat(xhr.responseText);  // Get the delivery cost
           var orderTotal = parseFloat(document.getElementById('subtotal').innerText);  // Get the order total from the page
           var amountDue = orderTotal + deliveryCost;  // Add delivery cost to order total

           document.getElementById('delivery-cost').innerHTML = xhr.responseText;
           document.getElementById('delivery-cost').innerText = deliveryCost.toFixed(2);
           document.getElementById('amount-due').innerText = amountDue.toFixed(2);  // Update the total amount due

           // update the hidden input value with the total amount due
           document.getElementById('hidden-amount-due').value = amountDue;
          }
        };
       xhr.send('county=' + county + '&constituency=' + constituency);
      }

      /* ---------------------
        GET EXPECTED DATE FOR EVERY CONSTITUTENCY
      ---------------------------------*/
      function calculateExpectedDate() {
        var constituency = document.getElementById('constituency').value;
        var county = document.getElementById('county').value;

        if (constituency === "") {
            document.getElementById('expected-date').innerHTML = 'Expected Date: Not Available';
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'location_api/get_expected_day.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
            var expectedDays = parseInt(xhr.responseText); // Get the expected delivery days

            if (isNaN(expectedDays) || expectedDays <= 0) {
              document.getElementById('expected-date').innerHTML = 'Expected Date: Not Available';
              return;
            }

            // Calculate the expected date
            var today = new Date();
            today.setDate(today.getDate() + expectedDays); // Add the expected days to today's date
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var expectedDate = today.toLocaleDateString('en-US', options);
            // Update the expected date in the UI
            document.getElementById('expected-date').innerHTML = ` ${expectedDate}`;

            // Format the date for DATETIME (e.g., 2024-12-18 00:00:00)
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            var day = String(today.getDate()).padStart(2, '0');
            var formattedDate = `${year}-${month}-${day} 23:59:59`; // Default time to midnight
            // update the hidden input value with the expected date
            document.getElementById('hidden-expected-date').value = formattedDate;
          }
        };
        xhr.send('county=' + encodeURIComponent(county) + '&constituency=' + encodeURIComponent(constituency));
      }

      /*----------- CHECKOUT ------------*/
      document.getElementById('checkoutForm').addEventListener('submit', function (e) {
          e.preventDefault();

          // Get the button that was clicked
          var clickedButton = document.activeElement;

          // Check if "Confirm Payment" button was clicked
          if (clickedButton && clickedButton.id === 'confirmPaymentBtn') {
              var phone_number = document.getElementById('textInput').value;
              var amount = document.getElementById('hidden-amount-due').value;
              var expected_date = document.getElementById('hidden-expected-date').value;

              // Proceed with the payment process only for M-Pesa
              var xhr = new XMLHttpRequest();
              xhr.open('POST', 'mpesa_api/initiate_payment.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              xhr.onload = function () {
                  if (xhr.status === 200) {
                      try {
                          var response = JSON.parse(xhr.responseText);
                          if (response.status === 'success') {
                              Swal.fire({
                                icon: 'success',
                                title: 'Payment Successful',
                                html: 'Your payment of KES ' + amount + ' has been received!<br><b>Your Order Submitted Successfully</b>'
                              }).then(() => {
                                // Change the form action to mpesa_order.php
                                 document.getElementById('checkoutForm').action = 'mpesa_api/mpesa_order.php';
                                 // Submit the form
                                 document.getElementById('checkoutForm').submit();
                              });
                          } else {
                              Swal.fire({
                                icon: 'error',
                                title: 'Payment Failed',
                                text: response.message || 'There was an issue with your payment. Please try again.'
                              });
                          }
                      } catch (e) {
                          Swal.fire({
                            icon: 'error',
                            title: 'Error Parsing Response',
                            text: 'There was an error processing your request. Please try again.'
                          });
                      }
                  } else {
                      Swal.fire({
                        icon: 'error',
                        title: 'Payment Failed',
                        text: 'There was an issue with your payment. Please try again.'
                      });
                  }
              };

              xhr.send('phone_number=' + phone_number + '&amount=' + amount);
          }  else if (clickedButton && clickedButton.id === 'submitOrderBtn') {
           // Handle "Submit Order" if it's clicked (for non-Mpesa payments)
           let timerInterval;
           Swal.fire({
             title: "",
             html: "Submitting Order...",
             timer: 2000,
             timerProgressBar: true,
             didOpen: () => {
               Swal.showLoading();
               const timer = Swal.getPopup().querySelector("b");
               timerInterval = setInterval(() => {
                 timer.textContent = `${Swal.getTimerLeft()}`;
               }, 100);
             },
             willClose: () => {
               clearInterval(timerInterval);
             }
           }).then(() => {
               // Continue with the form submission
               document.getElementById('checkoutForm').action = 'order.php';  // or any other logic you need
               document.getElementById('checkoutForm').submit();
           });
       }

          // else {
          //     // Handle "Submit Order" if it's clicked
          //     // You can proceed with the default form submission or handle it via AJAX
          //     document.getElementById('checkoutForm').submit();
          // }
      });
    </script>
  </body>
</html>
