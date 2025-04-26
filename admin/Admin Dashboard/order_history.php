<?php
  if (isset($_GET['order_history'])) {
    $userID = $_GET['order_history'];
    // get user details
    $get_user = "SELECT * FROM user_table WHERE user_id='$userID'";
    $result_user = mysqli_query($conn, $get_user);
    $row_user = mysqli_fetch_assoc($result_user);
    // get user's orders
    $get_orders = "SELECT * FROM `user_orders` WHERE user_id='$userID' ORDER BY order_date DESC";
    $exe_query = mysqli_query($conn, $get_orders);
    $row_count = mysqli_num_rows($exe_query);
  }

 ?>
<div class="header">
    <div class="left">
        <h1><?php echo $row_user['full_name'] ?></h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                    Dashboard
                  </a></li>
              /
              <li><a href="admin_profile.php?manage_customers">Customer MGT</a></li>
          /
          <li><a href="#" class="active"><?php echo '@'.$row_user['user_name'] ?></a></li>
        <?php } elseif ($role='store_manager') { ?>
              <li><a href="admin_profile.php?store_manager">
                    Dashboard
                  </a></li>
              /
              <li><a href="admin_profile.php?manage_customers">Customer MGT</a></li>
          /
          <li><a href="#" class="active"><?php echo '@'.$row_user['user_name'] ?></a></li>
        <?php } ?>
        </ul>
    </div>
</div>

<!-- End of Insights -->

<div class="bottom-data">
    <div class="orders">
        <div class="header">
            <i class='bx bx-receipt'></i>
            <h3>Order History</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Invoice No.</th>
                    <th>Amount Due</th>
                    <th>Total Ordered Products</th>
                    <th>Order Date</th>
                    <th>Payment Mode</th>
                    <th>Details</th>
                    <th>Order Status</th>
                    <th>Order Feedback</th>

                </tr>
            </thead>
            <tbody>
              <?php
                if ($exe_query) {
                  while ($row = mysqli_fetch_assoc($exe_query)) {
                    $feedback_type = '';
                    if ($row['feedback_type'] == 'positive') {
                      $feedback_type = 'complete';
                    }elseif ($row['feedback_type'] == 'negative') {
                      $feedback_type = 'cancelled';
                    }
                    $payment_mode = $row['payment_mode'] == 'pay_with_mpesa' ? 'Paid Via Mpesa' : 'Pay On Delivery';
                     ?>
                    <tr>
                        <td>
                            <p><?php echo $row['invoice_number'] ?></p>
                        </td>
                        <td><?php echo 'Kshs.'.number_format($row['amount_due'], 0, '.', ',') ?></td>
                        <td><?php echo $row['total_products'] ?></td>
                        <td><?php echo formatDateTime($row['order_date']) ?></td>
                        <td><?php echo $payment_mode ?></td>
                        <td>
                          <button class="status view-details-btn" data-invoice="<?php echo $row['invoice_number']; ?>" data-order-status="<?php echo $row['order_status']; ?>">
                            Order Details
                          </button>
                        </td>
                        <td><span class="status <?php echo $row['order_status'] ?>"><?php echo $row['order_status'] ?></span></td>
                        <td><span class="status <?php echo $feedback_type ?>"><?php echo $row['order_feedback'] ?></span></td>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function (event){
      if (event.target.classList.contains('view-details-btn')) {
        const button = event.target;
        const invoiceNumber = button.dataset.invoice;
        const orderStatus = button.getAttribute('data-order-status');

        // Fetch order details for the given invoice numberh
        fetch(`../../functions/fetch_order_details.php?invoice_number=${invoiceNumber}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const userDetails = data.order_user_details;

              let contactMobileNo = userDetails.contact_number;
              let paymentMode = userDetails.payment_mode; // Original value from the database

              // Format the payment mode based on the order status
              if (paymentMode === 'pay_on_delivery' && orderStatus === 'complete') {
                paymentMode = 'Paid On Delivery';
              }else if (paymentMode === 'pay_with_mpesa') {
                paymentMode = 'Paid With Mpesa'
              } else {
                // General formatting: replace underscores and capitalize
                paymentMode = paymentMode
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
              }

              function formatDateString(dateString) {
                const date = new Date(dateString);
                if (isNaN(date)) {
                    return 'Invalid Date';
                }
                if (date == 'NULL') {
                  return 'Awaiting Delivery'
                }else {
                  const day = String(date.getDate()).padStart(2, '0'); // Day with leading zeros
                  const monthShort = date.toLocaleString('en-US', { month: 'short' }); // Short month name
                  const year = String(date.getFullYear()).slice(-2); // Last 2 digits of the year
                  const hours = String(date.getHours()).padStart(2, '0'); // Hours with leading zeros
                  const minutes = String(date.getMinutes()).padStart(2, '0'); // Minutes with leading zeros
                  const seconds = String(date.getSeconds()).padStart(2, '0'); // Seconds with leading zeros

                  // Format as "16-Dec-24 00:00:00"
                  return `${day}-${monthShort}-${year} ${hours}:${minutes}:${seconds}`;
                }

              }

              // Example usage
              let expectedDate = userDetails.expected_date;
              let formattedDate = formatDateString(expectedDate);

              let delivered_date = userDetails.delivered_date;
              // if (delivered_date == '01-Jan-70 02:00:00') {
              //   let formatted_delDate = 'Awaiting Delivery'
              // }
              // else {
                let formatted_delDate = formatDateString(delivered_date);
              // }

              const rows = data.products.map(product => `
                <tr>
                  <td class='thumbnail'>
                    <img src='../../product_imgs/${product.image}' alt='Product Image' style='width: 60px; height: 60px; object-fit: cover;'>
                  </td>
                  <td>${product.name}</td>
                  <td>${product.quantity}</td>
                  <td>Kshs.${product.price}</td>
                  <td>Kshs.${product.subtotal}</td>
                </tr>
              `).join('');

              Swal.fire({
                title: `Invoice Number: ${invoiceNumber}`,
                html: `
                  <h5 style='color: rgb(13 202 240)'>User Info</h5>
                  <div style='display: flex; flex-wrap: wrap; justify-content: space-around;'>
                    <p><b>Full Name:</b> ${userDetails.full_name}</p>
                    <p><b>User_Name:</b> ${userDetails.user_name}</p>
                    <p><b>Email:</b> ${userDetails.user_email}</p>
                    <p><b>Acc Mobile No.:</b> ${userDetails.user_mobile}</p>
                  </div>
                  <br>
                  <hr>
                  <h5 style='color: rgb(13 202 240)'>Order Info</h5>
                  <div style='display: flex; flex-wrap: wrap; justify-content: space-around; padding: 30px;'>
                    <p><b>Pickup Address:</b> ${userDetails.order_address}</p>
                    <p><b>Expected Date:</b> ${formattedDate}</p>
                    <p><b>Delivered Date:</b> ${delivered_date}</p>
                    <p><b>Order Contact Mobile No.:</b> ${contactMobileNo}</p>
                    <p><b>Payment Mode:</b> ${paymentMode}</p>
                  </div>
                  <br>
                  <table class='modal-table'>
                    <thead>
                      <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      ${rows}
                    </tbody>
                  </table>
                `,
                showCloseButton: true,
                confirmButtonText: 'Close'
              });
            } else {
              Swal.fire('Error', 'Failed to fetch order details.', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Something went wrong.', 'error');
          });
      }
    });
  });
</script>
