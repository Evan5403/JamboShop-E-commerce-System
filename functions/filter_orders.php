<?php
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  // Fetch filter inputs
  $user_role = isset($_POST['user_role']) ? $_POST['user_role'] : 'client'; // Default to client if not provided
  $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : 'All';
  $date_range = isset($_POST['date_range']) ? mysqli_real_escape_string($conn, $_POST['date_range']) : 'All';
  $username = isset($_POST['user']) ? $_POST['user'] : null; // For client-side

  if ($user_role === 'admin') {
    // Admin-side logic
    $query = "SELECT * FROM user_orders WHERE 1=1"; // Fetch all orders, no user-specific filtering

    // Filter by order status
    if ($status !== 'All') {
      $query .= " AND order_status = '$status'";
    }

    // Filter by date range
    if ($date_range !== 'All') {
      switch ($date_range) {
        case 'Today':
          $query .= " AND DATE(order_date) = CURDATE()";
          break;
        case 'Yesterday':
          $query .= " AND DATE(order_date) = CURDATE() - INTERVAL 1 DAY";
          break;
        case 'Last 7 Days':
          $query .= " AND order_date >= CURDATE() - INTERVAL 7 DAY";
          break;
        case 'Last Month':
          $query .= " AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                      AND YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)";
          break;
      }
    }

    // Execute the query
    $result = mysqli_query($conn, $query);
    // var_dump(mysqli_num_rows($result));
    // die;
    if (mysqli_num_rows($result) > 0) {
      $num = 0;
      while ($order = mysqli_fetch_assoc($result)) {
        $order_id = $order['order_id'];
        $user_id = $order['user_id'];
        $amount_due = $order['amount_due'];
        $invoice_number = $order['invoice_number'];
        $total_products = $order['total_products'];
        $order_date = $order['order_date'];
        $timestamp_o = strtotime($order_date); // Convert to timestamp
        $formatted_date_o = date('d-M-y H:i:s', $timestamp_o);
        $payment_mode = $order['payment_mode'];
        $contact_number = $order['contact_number'];
        $contact_email = $order['contact_email'];
        $address = $order['address'];
        $order_status = $order['order_status'];
        $num += 1;
          ?>
          <tr>
            <td><?php echo $num; ?></td>
            <td><?php echo $amount_due; ?></td>
            <td><?php echo $invoice_number; ?></td>
            <td><?php echo $total_products; ?></td>
            <td><button type='button' name='view_orders_btn' class='btn btn-secondary p-1' data-bs-toggle='modal' data-bs-target='<?php echo '#'. $invoice_number ?>'> View
            </button></td>
            <td><?php echo $formatted_date_o; ?></td>
            <td><button type='button' name='view_orders_btn' class='btn btn-secondary p-2 action-btn' data-order-id="<?php echo $order_id; ?>" data-invoice-number="<?php echo $invoice_number; ?>" data-order-status="<?php echo $order_status; ?>"> Action
            </button></td>
            <td><?php echo $order_status; ?></td>
          </tr>
          <?php
      }
    } else {
        echo "<tr><td colspan='6'>No orders found for the selected filters.</td></tr>";
    }
  }else {
    // GET USER ID
    $get_user = "SELECT * FROM user_table WHERE user_name='$username'";
    $result_user = mysqli_query($conn, $get_user);
    $row_user = mysqli_fetch_assoc($result_user);
    $user_id = (int) $row_user['user_id'];

    $query = "SELECT * FROM user_orders WHERE user_id=$user_id";

    // Filter by order status
    if ($status !== 'All') {
        $query .= " AND order_status = '$status'";
    }

    // Filter by date range
    if ($date_range !== 'All') {
      switch ($date_range) {
        case 'Today':
          $query .= " AND DATE(order_date) = CURDATE()";
          break;
        case 'Yesterday':
          $query .= " AND DATE(order_date) = CURDATE() - INTERVAL 1 DAY";
          break;
        case 'Last 7 Days':
          $query .= " AND order_date >= CURDATE() - INTERVAL 7 DAY";
          break;
        case 'Last Month':
          $query .= " AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                      AND YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)";
          break;
      }
    }

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($order = mysqli_fetch_assoc($result)) {
          $timestamp_o = strtotime($order['order_date']); // Convert to timestamp
          $formatted_date_o = date('d-M-y H:i:s', $timestamp_o);
          $timestamp_e = strtotime($order['expected_date']); // Convert to timestamp
          $formatted_date_e = date('d-M-y H:i:s', $timestamp_e);
          ?>
          <tr>
            <td><?php echo $order['invoice_number'] ?></td>
            <td><?php echo $order['total_products'] ?></td>
            <td><button class="btn view-details-btn" data-invoice="<?php echo $order['invoice_number']; ?>" data-order-status="<?php echo $order['order_status']; ?>">Order Details</button></td>
            <td>Kshs.<?php echo $order['amount_due'] ?></td>
            <td><?php
              if ($order['payment_mode'] == 'pay_on_delivery' && $order['order_status'] !== 'complete') {
                echo "Pay On Delivery";
              }elseif ($order['payment_mode'] == 'pay_with_mpesa') {
                echo "Paid Via Mpesa";
              }else {
                echo "Paid";
              }
            ?></td>
            <td><?php echo $formatted_date_o ?></td>
            <td><?php echo $formatted_date_e ?></td>
            <td><button class="btn action-btn"
                        data-invoice="<?php echo $order['invoice_number']; ?>"
                        data-status="<?php echo $order['order_status']; ?>">Action
            </button></td>
            <td> <span class="status <?php echo $order['order_status'] ?>"><?php echo $order['order_status'] ?></span> </td>
            <td><?php
             if ($order['order_feedback'] == '') {
               echo "<b> - Add your order review by clicking the 'Action button'</b>";
             }
             echo $order['order_feedback'];
            ?></td>
          </tr>
      <?php  }
    } else {
      echo "<p>No orders found for the selected filters.</p>";
    }
  }

  // var_dump($_POST);
  // die;
  // Build the query


?>
