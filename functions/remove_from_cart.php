<?php
  include('../includes/connect.php');

  function getIPAddress() {
    //whether ip is from the share internet
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
      }
    //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     }
     //whether ip is from the remote address
    else{
       $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  $data = json_decode(file_get_contents('php://input'), true);

  if (isset($data['product_id'])) {
    $product_id = intval($data['product_id']);
    $ip_address = getIPAddress(); // Ensure you have this function implemented

    $delete_query = "DELETE FROM `cart_details` WHERE product_id = $product_id AND ip_address = '$ip_address'";
    if (mysqli_query($conn, $delete_query)) {
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
  }
?>
