<?php
  session_start();
  include('../includes/connect.php'); // Replace with your actual database connection file
  include('../functions/common_functions.php');

  $user_id = '';
  // Get the user details
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_userId = "SELECT user_id FROM `user_table` WHERE user_name = '$username'";
    $result_query = mysqli_query($conn, $get_userId);
    $row = mysqli_fetch_assoc($result_query);
    $user_id = $row['user_id'];
  }


  // Fetch data for category breakdown
  $categoryQuery = " SELECT
                      vod.invoice_number,
                      uo.invoice_number,
                      uo.order_status,
                      c.category_title,
                      SUM(vod.quantity) AS total_purchases,
                      (SUM(vod.quantity) / (SELECT SUM(quantity) FROM view_order_details)) * 100 AS percentage
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
  $categoryResult = mysqli_query($conn, $categoryQuery);
  $categoryRows = mysqli_num_rows($categoryResult);
  $catData = [];

  $categories = [];
  $categoryCounts = [];
  $percentages = [];
  if ($categoryRows > 0) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
      $categories[] = $row['category_title'];
      // $categoryCounts[] = $row['total'];
      $percentages[] = round($row['percentage'], 2); // Round to 2 decimal places
      $catData[] = $row;
    }
  }

  // Fetch data for most purchased products
  $productQuery = "SELECT
                    product_title, SUM(quantity) AS total_purchased, view_order_details.invoice_number, user_orders.invoice_number, user_orders.order_status
                   FROM view_order_details
                   INNER JOIN products ON view_order_details.product_id = products.product_id
                   INNER JOIN user_orders ON view_order_details.invoice_number = user_orders.invoice_number
                   WHERE view_order_details.user_id='$user_id'
                   AND user_orders.order_status = 'complete'
                   GROUP BY product_title
                   ORDER BY total_purchased DESC
                   LIMIT 5";
  $productResult = mysqli_query($conn, $productQuery);
  $productRows = mysqli_num_rows($productResult);

  $products = [];
  $productCounts = [];
  if ($productRows > 0) {
    while ($row = mysqli_fetch_assoc($productResult)) {
        $products[] = $row['product_title'];
        $productCounts[] = $row['total_purchased'];
    }
  }

  $monthly_spent = "
            SELECT
              DATE_FORMAT(order_date, '%Y-%m') AS month,
              amount_due,
              order_status
            FROM
              user_orders
            WHERE
              user_id = '$user_id'
            AND
              order_status = 'delivered'
            OR
              order_status = 'complete'
            GROUP BY
              DATE_FORMAT(order_date, '%Y-%m')
            ORDER BY
              month ASC
          ";

  $monthly_spentResult = mysqli_query($conn, $monthly_spent);
  $spentResultRows = mysqli_num_rows($monthly_spentResult);
  $monthly_spentData = [];
  $amount_due = [];
  if ($spentResultRows > 0) {
    while ($row = mysqli_fetch_assoc($monthly_spentResult)) {
      $monthly_spentData[] = $row['month'];
      $amount_due[] = $row['amount_due'];
    }
  }

  // Fetch data for this month and last month
  $performance_query = "
            SELECT
                DATE_FORMAT(uo.order_date, '%Y-%m') AS month,
                COUNT(uo.order_id) AS total_orders,
                SUM(uo.amount_due) AS total_revenue,
                SUM(uo.total_products) AS total_sales,
                order_status AS order_status
            FROM
                user_orders uo
            WHERE
                uo.order_date >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01')
            AND
              uo.order_status = 'delivered'
            OR
              order_status = 'complete'
            GROUP BY
                DATE_FORMAT(uo.order_date, '%Y-%m')
            ORDER BY
                month ASC;
          ";

  $performance_result = mysqli_query($conn, $performance_query);
  $performance_data = [];
  if ($performance_result && mysqli_num_rows($performance_result) > 0) {
      while ($row = mysqli_fetch_assoc($performance_result)) {
          $performance_data[] = $row;
      }
  }

  // Fetch daily, weekly, and monthly sales trends
  $sales_trends_query = "
      SELECT
          DATE(order_date) AS date,
          WEEK(order_date) AS week,
          DATE_FORMAT(order_date, '%Y-%m') AS month,
          SUM(amount_due) AS daily_revenue,
          SUM(total_products) AS daily_sales,
          order_status AS order_status
      FROM
          user_orders
      WHERE
        order_status = 'delivered'
      OR
        order_status = 'complete'
      GROUP BY
          DATE(order_date), WEEK(order_date), DATE_FORMAT(order_date, '%Y-%m')
      ORDER BY
          date ASC;
  ";
  $sales_trends_result = mysqli_query($conn, $sales_trends_query);
  $sales_trends_data = [];
  if ($sales_trends_result && mysqli_num_rows($sales_trends_result) > 0) {
      while ($row = mysqli_fetch_assoc($sales_trends_result)) {
          $sales_trends_data[] = $row;
      }
  }

  // Fetch product performance (most sold products)
  $product_performance_query = "
      SELECT
          product_title,
          instock_sold AS total_sold
      FROM
          products
      GROUP BY
          product_id
      ORDER BY
          total_sold DESC
  ";
  $product_performance_result = mysqli_query($conn, $product_performance_query);
  $product_performance_data = [];
  if ($product_performance_result && mysqli_num_rows($product_performance_result) > 0) {
      while ($row = mysqli_fetch_assoc($product_performance_result)) {
          $product_performance_data[] = $row;
      }
  }

  // ORDER BY
  // total_sold DESC;

  // Return data as JSON
  echo json_encode([
    'catData' => $catData,
    'categories' => $categories,
    'percentages' => $percentages,
    'products' => $products,
    'productCounts' => $productCounts,
    'monthlySpent' => $monthly_spentData,
    'amountDue' => $amount_due,
    'performance_data' => $performance_data,
    'sales_trends' => $sales_trends_data,
    'product_performance' => $product_performance_data,
  ]);
?>
