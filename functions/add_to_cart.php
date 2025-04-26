


<?php

  // Database connection
  include('../includes/connect.php'); 

  // get ip address
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

  // Retrieve POST data
  $data = json_decode(file_get_contents('php://input'), true);
  // Debug incoming data
  file_put_contents('debug_log.txt', print_r($data, true)); // Log request data for debugging

  if (isset($data['product_id'])) {
    $product_id = intval($data['product_id']);
    $ip = getIPAddress();

    // Check if the product is already in the cart
    $check_cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip' AND product_id = $product_id";
    $result = mysqli_query($conn, $check_cart_query);

    if (mysqli_num_rows($result) > 0) {
     // Product already in the cart
     echo json_encode(['success' => false, 'message' => 'Product is already in the cart']);
    } else {
     // Insert into cart table
     $insert_cart_query = "INSERT INTO cart_details (product_id , ip_address, quantity) VALUES ($product_id, '$ip', 1)";
    if (mysqli_query($conn, $insert_cart_query)) {
      echo json_encode(['success' => true, 'message' => 'Product added to cart']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
    }
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
  }
?>
