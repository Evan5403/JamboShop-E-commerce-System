<?php

  $query_wishlist = "
      SELECT
        w.user_id,
        w.product_id,
        p.product_title,
        p.product_image1,
        p.price,
        p.average_rating
      FROM
        wishlist w
      INNER JOIN
        products p
      ON
        w.product_id = p.product_id
      WHERE
        w.user_id = '$user_id'
  ";

  $result_wishlist = mysqli_query($conn, $query_wishlist);
  $num_of_wishlist = mysqli_num_rows($result_wishlist);

  // GET USER TOTAL BOUGHT PRODUCTS
  $query_bought = "SELECT SUM(total_products) AS bought_products FROM user_orders WHERE user_id='$user_id' AND order_status='complete'";
  $result_bought = mysqli_query($conn, $query_bought);
  if ($result_bought) {
    $row = mysqli_fetch_assoc($result_bought);
    $bought_products = $row['bought_products'];
  }

  // GET USER'S TOTAL REVIEWS
  $get_total_reviews = "SELECT * FROM reviews WHERE user_id='$user_id'";
  $result_reviews = mysqli_query($conn, $get_total_reviews);
  $num_of_reviews = mysqli_num_rows($result_reviews);

  // GET USER'S TOTAL SPENT REVENUE
  $query_revenue = "SELECT SUM(amount_due) AS total_revenue FROM user_orders WHERE user_id='$user_id' AND order_status='complete'";
  $result_revenue = mysqli_query($conn, $query_revenue);
  if ($result_revenue) {
    $row = mysqli_fetch_assoc($result_revenue);
    $total_revenue = $row['total_revenue'];
  }

 ?>
<!-- ======================= Cards ================== -->
<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"><?php echo $num_of_wishlist ?></div>
            <div class="cardName">Wishlisted Products</div>
        </div>

        <div class="iconBx">
            <ion-icon name="heart-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"><?php echo $bought_products ?></div>
            <div class="cardName">Bought Products</div>
        </div>

        <div class="iconBx">
            <ion-icon name="cart-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"><?php echo $num_of_reviews ?></div>
            <div class="cardName">Comments On Products</div>
        </div>

        <div class="iconBx">
            <ion-icon name="chatbubbles-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers">Kshs.<?php echo number_format($total_revenue) ?></div>
            <div class="cardName">Spent Revenue</div>
        </div>

        <div class="iconBx">
            <ion-icon name="cash-outline"></ion-icon>
        </div>
    </div>
</div>

<!-- ================ Order Details List ================= -->
<div class="details">
  <div class="recentOrders">
      <div class="cardHeader">
        <h2>Recent Orders</h2>
        <a href="user_profile.php?my_orders" class="btn">View All</a>
      </div>
      <?php
        $username = $_SESSION['username'];
        $get_user_deatils = "SELECT * FROM `user_table` WHERE user_name='$username'";
        $result_query = mysqli_query($conn, $get_user_deatils);
        $row_query = mysqli_fetch_array($result_query);
        $user_id = $row_query['user_id'];
        $get_orders = "SELECT * FROM `user_orders` WHERE user_id='$user_id' ORDER BY order_date DESC LIMIT 3";
        $result_orders = mysqli_query($conn,$get_orders);
        $row_count = mysqli_num_rows($result_orders);
        if ($row_count == 0) {
          echo "<h2 style='color: red; text-align: center;'> You have not ordered anything yet";
          // exit();
        } else { ?>
          <table>
              <thead>
                  <tr>
                    <td>Invoice Number</td>
                    <td>Total Ordered Products</td>
                    <td>Amount Due</td>
                    <td>Payment</td>
                    <td>Order Date</td>
                    <td>Status</td>
                  </tr>
              </thead>

              <tbody>
                <?php
                 while ($row_order = mysqli_fetch_assoc($result_orders)) {
                   $order_id = $row_order['order_id'];
                   $amount_due = $row_order['amount_due'];
                   $total_products = $row_order['total_products'];
                   $order_date = $row_order['order_date'];
                   $timestamp_o = strtotime($order_date); // Convert to timestamp
                   $formatted_date_o = date('d-M-y H:i:s', $timestamp_o);
                   $payment_mode = $row_order['payment_mode'];
                   $invoice_number = $row_order['invoice_number'];
                   $order_status = $row_order['order_status'];
                     ?>
                     <tr>
                       <td><?php echo $invoice_number ?></td>
                       <td><?php echo $total_products ?></td>
                       <td>Kshs.<?php echo $amount_due ?></td>
                       <td><?php
                         if ($payment_mode == 'pay_on_delivery' && $order_status !== 'complete') {
                           echo "Pay On Delivery";
                         }elseif ($payment_mode == 'pay_with_mpesa') {
                           echo "Paid";
                         }else {
                           echo "Paid";
                         }
                       ?></td>
                       <td><?php echo $formatted_date_o ?></td>
                       <td> <span class="status <?php echo ($order_status == 'complete') ? "delivered" : $order_status ?>"><?php echo $order_status ?></span> </td>
                     </tr>
                 <?php } ?>
              </tbody>
          </table>
        <?php } ?>
  </div>

  <!-- ================= New Customers ================ -->
  <div class="recentCustomers">
        <div class="cardHeader">
            <h2>Recent Wishlisted Products</h2>
        </div>

        <table>
          <?php

            while ($row = mysqli_fetch_assoc($result_wishlist)) { ?>
              <tr>
                  <td width="60px">
                      <div class="imgBx"><img src="../../product_imgs/<?php echo $row['product_image1'] ?>" alt=""></div>
                  </td>
                  <td>
                      <h4><?php echo $row['product_title'] ?> <br> <span><?php echo 'Average Ratings: ' . $row['average_rating'] ?></span></h4>
                  </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>
