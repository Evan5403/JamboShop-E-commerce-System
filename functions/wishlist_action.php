<?php
  session_start();
  include('../includes/connect.php');

  $data = json_decode(file_get_contents('php://input'), true);
  $product_id = intval($data['product_id']);
  $action = $data['action'];

  $response = ['success' => false];

  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_userId = "SELECT * FROM `user_table` WHERE user_name='$username'";
    $result_query = mysqli_query($conn, $get_userId);
    $row = mysqli_fetch_assoc($result_query);
    $user_id = $row['user_id'];

    if ($user_id && $product_id) {
      if ($action === 'add') {
        // Add product to wishlist
        $query = "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id') ON DUPLICATE KEY UPDATE product_id = product_id";
      } elseif ($action === 'remove') {
        // Remove product from wishlist
        $query = "DELETE FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'";
      }

      if (mysqli_query($conn, $query)) {
        $response['success'] = true;
      }
    }
  } else {
    $response['message'] = 'Please signin/signup to manage your wishlist.';
  }

  echo json_encode($response);
?>
