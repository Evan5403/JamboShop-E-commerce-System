<?php
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Pragma: no-cache");
  header("Expires: 0");
  include('../includes/connect.php'); // Replace with your actual database connection file
  include('../functions/common_functions.php');

  header('Content-Type: application/json');

  try {
    // Query to fetch active cart promotions
    $query = "SELECT discount_value, minimum_cart_value
              FROM promotions
              WHERE applicable_to = 'cart' AND status = 'active'";
    $result = mysqli_query($conn, $query);

    $promotions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $promotions[] = [
          'discount_value' => (float) $row['discount_value'], // Discount percentage
          'min_cart_value' => (float) $row['minimum_cart_value'] // Minimum cart value for the promotion
        ];
    }

    echo json_encode([
      'success' => true,
      'promotions' => $promotions
    ]);
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'message' => $e->getMessage()
    ]);
  }
?>
