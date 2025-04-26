<?php
include('../includes/connect.php'); // Replace with your actual database connection file
include('../functions/common_functions.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['product_id']) && isset($input['quantity'])) {
  $product_id = $input['product_id'];
  $quantity = $input['quantity'];
  $ip = getIPAddress();

  // Fetch product/flashsale stock
  $check_instock = "SELECT
                      COALESCE(
                        fs.stock_limit,
                        p.instock
                      ) AS final_instock
                    FROM
                      products p
                    LEFT JOIN
                      (
                        SELECT
                          applicable_id AS product_id,
                          stock_limit
                        FROM
                          flash_sales
                        WHERE
                          status = 'active'
                      ) fs
                    ON
                      p.product_id = fs.product_id
                    WHERE p.product_id = '$product_id'";
  $result_instock = mysqli_query($conn, $check_instock);
  $row = mysqli_fetch_assoc($result_instock);

  if ($quantity > $row['final_instock']) {
    echo json_encode(['success' => false, 'message' => 'Quantity exceeds available stock.']);
    exit;
  }

  $update_query = "UPDATE `cart_details` SET `quantity`='$quantity' WHERE `product_id`='$product_id' AND `ip_address`='$ip'";
  $result = mysqli_query($conn, $update_query);

  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
