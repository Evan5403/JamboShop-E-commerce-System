<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <!-- bootstrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css file -->
    <link rel="stylesheet" href="../style.css">

    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <?php
      session_start();
      include('../../includes/connect.php');
      include('../../functions/common_functions.php');
      if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $get_user_query = "SELECT * FROM user_table WHERE user_name='$username'";
        $result = mysqli_query($conn, $get_user_query);
        $run_query = mysqli_fetch_array($result);
        $user_id = $run_query['user_id'];
        // var_dump($user_id);
        // die;
      }

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $payment_mode = "pay_with_mpesa";
        $get_ip = getIPAddress();
        $amount_due = $_POST['amount'];
        $expected_date = $_POST['expected_date'];
        $contact_number = (int) $_POST['contact_number'];
        $contact_email = $_POST['contact_email'];
        $address = $_POST['address'];
        $invoice_number = mt_rand();
        $total_price = 0;
        $arr = [];
        $arr_qty = [];
        $total_quantity = 0;
        $status = "pending";
        $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip'";
        $result_cart_price = mysqli_query($conn, $cart_query);
        $count = mysqli_num_rows($result_cart_price);
        while ($row_price = mysqli_fetch_array($result_cart_price)) {
          $product_id = $row_price['product_id'];
          // get products
          $select_products = "SELECT
                                p.product_id,
                                p.instock,
                                COALESCE(
                                  (p.price - (p.price * (fs.discount_value / 100))),
                                  pp.display_price
                                ) AS final_price,
                                pp.promotion_id,
                                pp.original_price,
                                fs.qty_sold
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
                                    qty_sold
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
          $instock = (int) $row_product_price['instock'];
          $fetch_qty = "SELECT * FROM `cart_details` WHERE product_id='$product_id'";
          $execute_query = mysqli_query($conn, $fetch_qty);
          $row_price = mysqli_fetch_array($execute_query);
          $cart_qty = (int) $row_price['quantity'];

          // update flashsale qty_sold and product instock
          $qty_sold = isset($row_product_price['qty_sold']) ? (int) $row_product_price['qty_sold'] : null;
          if ($qty_sold !== null) {
            // Update flash sales qty_sold and product instock
            $qty_sold += $cart_qty;
            $instock -= $cart_qty;

            $updateFlashSales = "UPDATE flash_sales
                                 SET qty_sold = $qty_sold
                                 WHERE applicable_id = '$product_id' AND status = 'active'";
            mysqli_query($conn, $updateFlashSales);

            $updateProductStock = "UPDATE products
                                   SET instock = $instock
                                   WHERE product_id = '$product_id'";
            mysqli_query($conn, $updateProductStock);
          } elseif ($row_product_price['promotion_id'] !== null) {
            // Product is in promotions
            $instock -= $cart_qty;

            $updateProductStock = "UPDATE products
                                   SET instock = $instock
                                   WHERE product_id = '$product_id'";
            mysqli_query($conn, $updateProductStock);
          } else {
            // Product is only in products table
            $instock -= $cart_qty;

            $updateProductStock = "UPDATE products
                                   SET instock = $instock
                                   WHERE product_id = '$product_id'";
            mysqli_query($conn, $updateProductStock);
          }

          $arr_qty[] += $cart_qty;
          $total_quantity = array_sum($arr_qty);
          $calculation = $product_price * $cart_qty;
          // insert into view_order_deatil table for reports
          $view_order_deatils = "INSERT INTO `view_order_details`
                              (invoice_number, user_id, product_id, quantity, price, subtotal, order_date)
                            VALUES
                              ($invoice_number, $user_id, $product_id, $cart_qty, $product_price, $calculation, NOW())";
          $execute_sql = mysqli_query($conn, $view_order_deatils);

          $arr[] += $calculation;
          $total_price = array_sum($arr);
        }
        $insert_orders = "INSERT INTO `user_orders`
                            (user_id, amount_due, invoice_number, total_products, order_date, expected_date, payment_mode, contact_number, contact_email, address, order_status)
                          VALUES
                            ('$user_id', $amount_due, $invoice_number, $total_quantity, NOW(),'$expected_date','$payment_mode','$contact_number','$contact_email','$address','$status')";
        $result_query = mysqli_query($conn, $insert_orders);
        if ($result_query) {
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "success",
              title: "Orders submitted successfully",
              showConfirmButton: false,
              timer: 2000
            }).then(() => {
              window.open('../user_dashboard/user_profile.php','_self');
            });
          </script>
          <?php
        }
        // empty cart
        $empty_cart = "DELETE FROM cart_details WHERE ip_address='$get_ip'";
        $result_delete = mysqli_query($conn, $empty_cart);
        // var_dump($payment_mode);
      }
      // die;

     ?>

    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- script to show active links in the side nav -->
    <script type="text/javascript" src="../script/index.js"></script>

    <!-- bootstrap jquery link -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  </body>
</html>
