<?php
  include('../includes/connect.php'); // Replace with your actual database connection file
  include('../functions/common_functions.php');

  if (isset($_GET['invoice_number'])) {
    $invoice_number = $_GET['invoice_number'];

    // Fetch order and user details
    $query_order_user = "
      SELECT
        u.full_name AS full_name,
        u.user_name AS user_name,
        u.user_email AS user_email,
        u.user_mobile AS user_mobile,
        o.payment_mode AS payment_mode,
        o.contact_number AS contact_number,
        o.contact_email AS contact_email,
        o.address AS order_address,
        o.expected_date AS expected_date,
        o.delivered_date AS delivered_date,
        o.order_status AS order_status
      FROM
        user_orders o
      JOIN
        user_table u ON o.user_id = u.user_id
      WHERE
        o.invoice_number = '$invoice_number'
    ";
    $result_order_user = mysqli_query($conn, $query_order_user);

    if ($result_order_user && mysqli_num_rows($result_order_user) > 0) {
      $order_user_details = mysqli_fetch_assoc($result_order_user);

      // Fetch product details
      $query_products = "
        SELECT
            v.product_id,
            v.quantity,
            v.price,
            v.subtotal,
            p.product_title AS name,
            p.product_image1 AS image
        FROM
            view_order_details v
        JOIN
            products p ON v.product_id = p.product_id
        WHERE
            v.invoice_number = '$invoice_number'
      ";
      $result_products = mysqli_query($conn, $query_products);

      $products = [];
      if ($result_products && mysqli_num_rows($result_products) > 0) {
        while ($row = mysqli_fetch_assoc($result_products)) {
            $products[] = $row;
        }
      }

      // Combine all data and send as JSON
      echo json_encode([
        'success' => true,
        'order_user_details' => $order_user_details,
        'products' => $products,
      ]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Order not found.']);
    }
  } elseif ($_GET['product_id']) {
    $productID = $_GET['product_id'];

    // Fetch Product Reviews & Ratings
    $get_user_reviews = "SELECT
                        reviews.rating,
                        reviews.review_summary,
                        reviews.review_text,
                        reviews.created_at,
                        user_table.user_id,
                        user_table.user_name,
                        user_table.user_image
                      FROM
                        reviews
                      INNER JOIN
                        user_table
                      ON
                        reviews.user_id = user_table.user_id
                      WHERE
                        reviews.product_id = '$productID'";
    $result_reviews = mysqli_query($conn, $get_user_reviews);
    $reviews_num_rows = mysqli_num_rows($result_reviews);

    $user_reviews = [];
    if ($result_reviews && $reviews_num_rows > 0) {
      while ($row = mysqli_fetch_assoc($result_reviews)) {
          $user_reviews[] = $row;
      }

      echo json_encode([
        'success' => true,
        'user_reviews' => $user_reviews,
      ]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
  }
?>
