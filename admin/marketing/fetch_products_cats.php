<?php

  // Database connection
  include('../../includes/connect.php');

  // Get the 'applicable_to' value from the request
  $applicable_to = $_GET['applicable_to'] ?? '';

  if ($applicable_to === 'product') {
    $query = "SELECT product_id , product_title FROM products"; // Adjust column names as needed
    $result = mysqli_query($conn, $query);

    // Fetch the results into an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = [
        'id' => $row['product_id'],  // Replace with your database column name
        'name' => $row['product_title'] // Replace with your database column name
      ];
    }
  } elseif ($applicable_to === 'category') {
    $query = "SELECT category_id , category_title FROM categories"; // Adjust column names as needed
    $result = mysqli_query($conn, $query);

    // Fetch the results into an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = [
        'id' => $row['category_id'],  // Replace with your database column name
        'name' => $row['category_title'] // Replace with your database column name
      ];
    }
  } else {
    echo json_encode([]); // Return an empty array for 'cart'
    exit;
  }

  // Execute the query


  if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
    exit;
  }



  // Output the data as JSON
  echo json_encode($data);

 ?>
