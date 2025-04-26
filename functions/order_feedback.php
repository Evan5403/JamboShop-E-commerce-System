<?php
  include('../includes/connect.php');

  header('Content-Type: application/json');
  $data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['invoiceNumber']) || !isset($data['feedback'])) {
      echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
      exit();
  }

  $invoiceNumber = mysqli_real_escape_string($conn, $data['invoiceNumber']);
  $feedback = mysqli_real_escape_string($conn, $data['feedback']);
  $feedbackType = mysqli_real_escape_string($conn, $data['feedbackType']);

  $query = "UPDATE `user_orders` SET `order_feedback` = '$feedback', `feedback_type` = '$feedbackType' WHERE `invoice_number` = '$invoiceNumber'";
  $result = mysqli_query($conn, $query);

  if ($result) {
      echo json_encode(['success' => true, 'message' => 'We are grateful for you feedback, which helps us grow and serve you better']);
  } else {
      echo json_encode(['success' => false, 'message' => 'Failed to submit feedback']);
  }
  mysqli_close($conn);
?>
