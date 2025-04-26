
<?php
  session_start();
  include('../includes/connect.php');
  include('common_functions.php');

  header('Content-Type: application/json');
  $data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['invoiceNumber']) || !isset($data['reason'])) {
      echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
      exit();
  }

  $invoiceNumber = mysqli_real_escape_string($conn, $data['invoiceNumber']);
  $reason = mysqli_real_escape_string($conn, $data['reason']);
  $cancel_by = mysqli_real_escape_string($conn, $data['user']);

  $update_order_status = "UPDATE `user_orders` SET `order_status` = 'cancelled' WHERE `invoice_number` = '$invoiceNumber'";
  $result = mysqli_query($conn, $update_order_status);


  $cancel_orders = "INSERT INTO canceled_orders (invoice_number , order_status, cancel_reason, canceled_by) VALUES ('$invoiceNumber', 'cancelled', '$reason', '$cancel_by')";
  $result_query = mysqli_query($conn, $cancel_orders);


  if ($result AND $result_query) {
    // update admin log
    if ($cancel_by == 'admin') {
      $admin = $_SESSION['admin'];
      $select_details = "SELECT * FROM admin_table WHERE user_name='$admin'";
      $exe_query = mysqli_query($conn, $select_details);
      $row_admin = mysqli_fetch_assoc($exe_query);
      $admin_id = $row_admin['admin_id'];
      $action = "Canceled an Order";
      $action_effect = "negative";
      $details = "Invoice No.: $invoiceNumber reason: $reason"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
    }
    // update instock
    $return_stock = "SELECT * FROM view_order_details WHERE invoice_number='$invoiceNumber'";
    $exe_query = mysqli_query($conn, $return_stock);
    if ($exe_query) {
      while ($row = mysqli_fetch_assoc($exe_query)) {
        $productID = $row['product_id'];
        $productStock = (int) $row['quantity'];
        $update_instock = "UPDATE `products` SET `instock` = `instock` + $productStock WHERE product_id = $productID";
        $result_update = mysqli_query($conn, $update_instock);
      }
    }
    echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
  } else {
      echo json_encode(['success' => false, 'message' => 'Failed to cancel the order']);
  }
  mysqli_close($conn);

?>
