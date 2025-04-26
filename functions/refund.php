
<?php
  include('../includes/connect.php');

  header('Content-Type: application/json');
  $data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['invoiceNumber'])) {
      echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
      exit();
  }

  $invoiceNumber = mysqli_real_escape_string($conn, $data['invoiceNumber']);

  $update_refund_status = "UPDATE `canceled_orders` SET `refund` = 'yes' WHERE `invoice_number` = '$invoiceNumber'";
  $result = mysqli_query($conn, $update_refund_status);


  if ($result) {
    echo json_encode(['success' => true, 'message' => 'Success']);
  } else {
      echo json_encode(['success' => false, 'message' => 'Failed to cancel the order']);
  }
  mysqli_close($conn);

?>
