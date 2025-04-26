<?php
  session_start();
  include('includes/connect.php');

  include('functions/common_functions.php');


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="style/style.css">
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
      <?php include('includes/header_nav.php') ?>

      <!-- MARGIN -->
      <main>

        <div class="single-cart">
          <div class="container">
            <div class="wrapper">
              <div class="breadcrumb">
                <ul class="flexitem">
                  <li><a href="index.php">Home</a></li>
                  <li>cart</li>
                </ul>
              </div>
              <div class="page-title">
                <h1>Shopping Cart</h1>
              </div>
              <div class="products one cart">
                <div class="flexwrap">
                  <form action="" class="form-cart">
                    <div class="item" id="cart-table1">
                      <table id="cart-table">
                        <tbody>
                          <?php
                            //  check if there cart table is empty
                            $discount = 0;
                            $tax = 0;
                            $ip = getIPAddress();
                            $total_price = 0;
                            $arr = [];
                            $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
                            $result = mysqli_query($conn,$cart_query); //execute sql command
                            $result_count = mysqli_num_rows($result); // count rows
                            if ($result_count > 0) {?>
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th>Price</th>
                                  <th>Qty</th>
                                  <th>Subtotal</th>
                                  <th></th>
                                </tr>
                              </thead>
                            <?php
                            // fetch product Details
                            while ($row = mysqli_fetch_array($result)){
                              $product_id = $row['product_id'];
                              $quantity = $row['quantity'];
                              $fetch_product_details = "SELECT
                                                          p.product_id,
                                                          p.product_title,
                                                          p.product_image1,
                                                          COALESCE(
                                                            (p.price - (p.price * (fs.discount_value / 100))),
                                                            pp.display_price
                                                          ) AS final_price,
                                                          COALESCE(
                                                            fs.discount_value, -- Use flash sale price if active
                                                            pp.discount_value -- Else use promotion price
                                                          ) AS final_discount_value,
                                                          COALESCE(
                                                            fs.qty_remaining,
                                                            p.instock
                                                          ) AS final_instock,
                                                          pp.promotion_id,
                                                          fs.stock_limit,
                                                          pp.display_price,
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
                                                              discount_value,
                                                              stock_limit,
                                                              qty_remaining
                                                            FROM
                                                              flash_sales
                                                            WHERE
                                                              status = 'active'
                                                          ) fs
                                                        ON
                                                            p.product_id = fs.product_id
                                                        WHERE
                                                          p.product_id = '$product_id'";
                              $result_product = mysqli_query($conn,$fetch_product_details);
                              while ($row_product = mysqli_fetch_array($result_product)){
                                $product_title = $row_product['product_title'];
                                $product_image1 = $row_product['product_image1'];
                                $promotion_id = $row_product['promotion_id'];
                                $price_table = floor($row_product['final_price']);
                                $formatted_price = number_format($price_table, 0, '.', ',');
                                $instock = (int) $row_product['final_instock'];
                                $stock_limit = (int) $row_product['stock_limit'];
                                ?>
                                <tr class="item-to-be-removed" data-product-id="<?php echo $product_id; ?>">
                                  <td class="flexitem">
                                    <div class="thumbnail object-cover">
                                      <a href="product-details.php?product=<?php echo $product_id ?>"><img src="product_imgs/<?php echo $product_image1 ?>" alt=""></a>
                                    </div>
                                    <div class="content">
                                      <strong><a href="product-details.php?product=<?php echo $product_id ?>"><?php echo $product_title ?></a></strong>
                                    </div>
                                  </td>
                                  <td data-price="<?php echo $price_table ?>">Kshs.<?php echo $price_table ?></td>
                                  <td>
                                    <sup class="stock-info" style="display: none"><?php echo $instock ?></sup>
                                    <div class="qty-control flexitem">
                                      <button class="minus" data-action="minus">-</button>
                                      <input type="number" value="<?php echo $quantity ?>" min="1" readonly>
                                      <button class="plus" data-action="plus">+</button>
                                    </div>
                                  </td>
                                  <td>Kshs.<?php echo $quantity * $price_table; ?></td>
                                  <?php
                                    $total_price = $quantity * $price_table;
                                    $arr[] += $total_price;
                                   ?>
                                  <td>
                                    <a href="javascript:void(0);" class="item-remove" data-product-id="<?php echo $product_id; ?>">
                                      <i class="ri-close-line"></i>
                                    </a>
                                  </td>
                                </tr>
                              <?php }}}else {?>
                              <div class="" style="padding: 20px;">
                                <p style="margin-bottom: 30px;">No Item In Cart </p>
                                <a href="index.php" class="primary-button">Go Shopping</a>
                              </div>
                            <?php  } ?>
                        </tbody>
                      </table>
                    </div>
                  </form>
                  <div class="cart-summary styled">
                    <div class="item">
                      <!-- <div class="coupon">
                        <input type="text" placeholder="Enter coupon">
                        <button>Apply</button>
                      </div> -->
                      <div class="cart-total">
                        <table>
                          <tbody>
                            <tr>
                              <th>Subtotal</th>
                              <td id="cart-total">Kshs.<?php echo number_format(array_sum($arr), 0, '.', ','); ?></td>
                            </tr>
                            <?php
                              // Calculate cart subtotal
                              $subtotal = array_sum($arr);

                              // Fetch active cart promotions
                              $query = "SELECT discount_value, minimum_cart_value
                                      FROM promotions
                                      WHERE applicable_to = 'cart' AND status = 'active'
                                      ORDER BY minimum_cart_value ASC";
                              $result = mysqli_query($conn, $query);

                              $discount = 0;
                              if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    if ($subtotal >= $row['minimum_cart_value']) {
                                        $discount = ($subtotal * $row['discount_value']) / 100;
                                    }
                                }
                              }

                              // Calculate total
                              $total = $subtotal - $discount;
                             ?>
                            <tr>
                              <th>Discount</th>
                              <td id="cart-discount">Kshs.<?php echo number_format($discount, 0, '.', ',') ?></td>
                            </tr>
                            <tr class="grand-total">
                              <th>TOTAL</th>
                              <td><strong>Kshs.<?php echo number_format($total, 0, '.', ',') ?></strong></td>
                            </tr>
                          </tbody>
                        </table>
                        <a href="users_area/checkout.php" class="secondary-button">Checkout</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </main>

      <!-- FOOTER & MENU-BUTTON & SEARCH-BUTTON -->
      <?php include('includes/footer-menu-button.php') ?>

      <!-- overlay -->
      <div class="overlay">

      </div>

      <?php
        $check_cart_discount = "SELECT * FROM `promotions` WHERE status='active' AND applicable_to='cart'";
        $exe_query = mysqli_query($conn,$check_cart_discount);
        $check_present = mysqli_num_rows($exe_query);

        if ($check_present > 0) {
          ?>
          <div id="modal" class="modal">
            <div class="content flexcol">
              <h2>Discount Alert!</h2>
              <?php
                while ($get_cart_discount = mysqli_fetch_assoc($exe_query)) {
                  $timestamp_o = strtotime($get_cart_discount['end_date']); // Convert to timestamp
                  $formatted_date_o = date('d-M-y H:i:s', $timestamp_o);
                  ?>
                  <p class=""><strong><?php echo $get_cart_discount['promotion_name'] ?></strong>: get <?php echo $get_cart_discount['discount_value'] ?>% off on orders above <?php echo $get_cart_discount['minimum_cart_value'] ?>/- <br> valid until <?php echo $formatted_date_o?></p>
              <?php } ?>
              <a href="javascript:void(0);" class="t-close modalclose flex-center">
                <i class="ri-close-line"></i>
              </a>
            </div>
          </div>
        <?php } ?>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.3.1/index.js"></script>
    <script src="script/script.js" charset="utf-8"></script>
  </body>
</html>
