
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
            <li><a href="index.php#featuredProducts">Featured Products</a></li>
            <li><a href="users_area/user_dashboard/user_profile.php?my_wishlist">Wishlist</a></li>
          </ul>
        </div>
        <div class="right">
          <ul class="flexitem main-links">
            <?php
              if (isset($_SESSION['username'])){?>
                <li><a href="users_area/user_dashboard/user_profile.php">My Account</a></li>
                <li><a href="users_area/user_logout.php">Logout</a></li>
             <?php } else {?>
               <li><a href="./users_area/user_login.php">Signup/Login</a></li>
             <?php } ?>
            <!-- <li><a href="#">USD <span class="icon-small"><i class="ri-arrow-down-s-line"></i></span> </a>
              <ul>
                <li class="current"><a href="#">USD</a></li>
                <li><a href="#"></a>EURO</li>
                <li><a href="#"></a>GBP</li>
              </ul>
            </li>
            <li><a href="#">English <span class="icon-small"><i class="ri-arrow-down-s-line"></i></span></a>
              <ul>
                <li class="current"><a href="#">English</a></li>
                <li><a href="#"></a>German</li>
                <li><a href="#"></a>Spanish</li>
              </ul>
            </li> -->
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
                                <li><a href="products.php?category=<?php echo $row_product['category_id'] ?>">
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
                                <li><a href="products.php?category=<?php echo $row_product['category_id'] ?>">
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
                                <li><a href="products.php?category=<?php echo $row_product['category_id'] ?>">
                                  <?php echo $row_product['category_title'] ?>
                                </a></li>
                            <?php  } ?>
                          </ul>
                        </div>
                      </div>
                      <!-- <div class="flexcol">
                        <div class="row">
                          <h4>Unisex</h4>
                          <ul> -->
                            <?php
                              // $get_kids_products = "SELECT
                              //                         p.product_id,
                              //                         p.demographic,
                              //                         p.category_title AS category_id,
                              //                         c.category_title
                              //                       FROM
                              //                         products p
                              //                       INNER JOIN
                              //                         categories c
                              //                       ON
                              //                           p.category_title = c.category_id
                              //                       WHERE demographic='unisex'
                              //                       GROUP BY c.category_title";
                              // $result_products = mysqli_query($conn,$get_kids_products);
                              // while ($row_product = mysqli_fetch_assoc($result_products)) {
                                ?>
                                <!-- <li><a href="products.php?category=<?php //echo $row_product['category_id'] ?>"> -->
                                  <?php //echo $row_product['category_title'] ?>
                                <!-- </a></li> -->
                            <?php // } ?>
                          <!-- </ul>
                        </div>
                      </div> -->
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
                                  <a href="#"><img src="product_imgs/<?php echo $row_product['product_image1'] ?>" alt=""></a>
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
                    <a href="products.php?category=<?php echo $category_id ?>"><?php echo $category_title ?>
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
                                  <img src="product_imgs/<?php echo $product_image1 ?>" alt="">
                                </a>
                              </div>
                              <div class="item-content">
                                <p><a href="product-details.php?product=<?php echo $product_id ?>"><?php echo $product_title ?></a></p>
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
                                <a href="javascript:void(0);"><img src="product_imgs/<?php echo $product_image1?>" alt=""></a>
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
                      <a href="users_area/checkout.php" class="primary-button">Checkout</a>
                      <a href="cart.php" class="secondary-button">View Cart</a>
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
