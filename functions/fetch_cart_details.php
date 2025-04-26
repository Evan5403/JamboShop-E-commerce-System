<?php
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Pragma: no-cache");
  header("Expires: 0");
  include('../includes/connect.php'); // Replace with your actual database connection file
  include('../functions/common_functions.php');

  // Determine the mode of request
  $mode = isset($_GET['mode']) ? $_GET['mode'] : 'mini-cart';

  $ip = getIPAddress();
  $total_price = 0;

  if ($mode === 'mini-cart' || $mode === 'both') {
    // Mini-cart section
    ?>
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
                    $price_table = floor($row_product['final_price']);
                    $instock = (int) $row_product['instock'];
                    $total_cost_price = floor($cart_qty * $row_product['final_price']);
                    $arr[] = $total_cost_price;
                  ?>
                  <li class="item">
                    <div class="thumbnail object-cover">
                      <a href="javascript:void(0);"><img src="product_imgs/<?php echo $product_image1?>" alt=""></a>
                    </div>
                    <div class="item-content">
                      <p><a href="javascript:void(0);"><?php echo $product_title ?></a></p>
                      <span class="price">
                        <span>Kshs.<?php echo $price_table ?></span>
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
            <p><strong>Kshs.<?php echo $total_cart_price; ?></strong></p>
          </div>
          <div class="actions">
            <a href="users_area/checkout.php" class="primary-button">Checkout</a>
            <a href="cart.php" class="secondary-button">View Cart</a>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  <?php }
  // Cart page section
  if ($mode === 'cart-page' || $mode === 'both') {
    ?>
    <table id="cart-table">
    <tbody>
      <?php
        //  check if there cart table is empty
        $ip = getIPAddress();
        $num = 0; // counting products sequentially
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
            $price_table = floor($row_product['final_price']);
            $instock = (int) $row_product['instock'];
            $product_price = array($row_product['final_price']);
            $product_values = array_sum($product_price);
            $total_price += $product_values;
            $num+=1;
            ?>
            <tr class="item-to-be-removed" data-product-id="<?php echo $product_id; ?>">
              <td class="flexitem">
                <div class="thumbnail object-cover">
                  <a href="javascript:void(0);"><img src="product_imgs/<?php echo $product_image1 ?>" alt=""></a>
                </div>
                <div class="content">
                  <strong><a href="javascript:void(0);"><?php echo $product_title ?></a></strong>
                  <!-- <p>Color: White</p> -->
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
              <td>Kshs.<?php echo $quantity * $price_table ?></td>
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
  <?php  }?>
