<?php
  session_start();
  include('../includes/connect.php');
  include('common_functions.php');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $invoiceNumber = $input['invoiceNumber'];
    $contactEmail = $input['contactEmail'];
    $action = $input['action'];

    if (empty($invoiceNumber) || empty($action)) {
      echo json_encode(['success' => false, 'message' => 'Invalid request.']);
      exit();
    }

    // Determine the new status
    $newStatus = '';
    switch ($action) {
      case 'processing':
        $newStatus = 'processing';
        break;
      case 'dispatched':
        $newStatus = 'dispatched';
        break;
      case 'delivered':
        $newStatus = 'delivered';
        break;
      case 'complete':
        $newStatus = 'complete';
        break;
      default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit();
    }

    // Update the delivery date if the status is "delivered"
    if ($newStatus === 'delivered') {
      $delivered_date = "UPDATE `user_orders` SET `delivered_date`=NOW() WHERE `invoice_number`='$invoiceNumber'";
      $result = mysqli_query($conn, $delivered_date);
    }

    // update stock_sold if order_status is "complete"
    if ($newStatus === 'complete') {
      $get_product_details = "SELECT * FROM view_order_details WHERE invoice_number=$invoiceNumber";
      $execute_sql = mysqli_query($conn, $get_product_details);
      while ($row = mysqli_fetch_assoc($execute_sql)) {
        $productID = $row['product_id'];
        $qty_sold = (int) $row['quantity'];
        $stmt = $conn->prepare("UPDATE `products` SET `instock_sold` = `instock_sold` + ? WHERE `product_id` = ?");
        $stmt->bind_param("ii", $qty_sold, $productID);
        $stmt->execute();
        $stmt->close();
      }
    }

    // Update the order status
    $query = "UPDATE user_orders SET order_status = ? WHERE invoice_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $newStatus, $invoiceNumber);

    if ($stmt->execute()) {
      if ($newStatus !== 'delivered') {
        $admin = $_SESSION['admin'];
        $select_details = "SELECT * FROM admin_table WHERE user_name='$admin'";
        $exe_query = mysqli_query($conn, $select_details);
        $row_admin = mysqli_fetch_assoc($exe_query);
        $admin_id = $row_admin['admin_id'];
        $action = "Updated an Order";
        $action_effect = "positive";
        $details = "Invoice No.: $invoiceNumber status: $newStatus"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }
      echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to update order status.']);
    }

    $stmt->close();
    $conn->close();
  }

?>
