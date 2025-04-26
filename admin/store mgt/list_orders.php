<?php
  $get_orders = "SELECT * FROM `user_orders` ORDER BY order_date DESC";
  $exe_query = mysqli_query($conn,$get_orders);
  $row_count = mysqli_num_rows($exe_query);
  if ($row_count==0) {
    echo "<h2 class='bg-danger text-center mt-5'>No Orders Yet</h2>";
  }else {
    ?>
    <h3 class="text-center text-success">All Orders</h3>
    <div class="filter-options">
      <div class="item-sortir">
        <div class="label">
          <span class="mobile-hide">Filter by Order Status</span>
          <i class="ri-arrow-down-s-line"></i>
        </div>
        <ul>
          <li><input type="radio" name="date_range" class="filter-date" value="All"> Default</li>
          <li><input type="radio" name="order_status" id="pending" class="filter-status" value="pending">
            <label for="pending">Pending</label>
          </li>
          <li><input type="radio" name="order_status" id="processing" class="filter-status" value="processing">
            <label for="processing">Processing</label>
          </li>
          <li><input type="radio" name="order_status" id="dispatched" class="filter-status" value="dispatched">
            <label for="dispatched">Dispatched</label>
          </li>
          <li><input type="radio" name="order_status" id="delivered" class="filter-status" value="delivered">
            <label for="delivered">Delivered</label>
          </li>
          <li><input type="radio" name="order_status" id="complete" class="filter-status" value="complete">
            <label for="complete">Complete</label>
          </li>
          <li><input type="radio" name="order_status" id="cancelled" class="filter-status" value="cancelled">
            <label for="cancelled">Cancelled</label>
          </li>
        </ul>
      </div>
      <div class="item-sortir">
        <div class="label">
          <span class="mobile-hide">Filter by Date Range</span>
          <i class="ri-arrow-down-s-line"></i>
        </div>
        <ul>
          <li><input type="radio" name="date_range" class="filter-date" value="All"> Default</li>
          <li><input type="radio" name="date_range" id="Today" class="filter-date" value="Today">
            <label for="Today">Today</label>
          </li>
          <li><input type="radio" name="date_range" id="Yesterday" class="filter-date" value="Yesterday">
            <label for="Yesterday">Yesterday</label>
          </li>
          <li><input type="radio" name="date_range" id="Last 7 Days" class="filter-date" value="Last 7 Days">
            <label for="Last 7 Days">Last 7 Days</label>
          </li>
          <li><input type="radio" name="date_range" id="Last Month" class="filter-date" value="Last Month">
            <label for="Last Month">Last Month</label>
          </li>
        </ul>
      </div>
    </div>
    <form class="" action="" method="post">
      <table class="table text-center table-bordered mt-5">

        <thead class='table-primary text-center'>
          <tr>
            <th>SI no.</th>
            <th>Due Amount</th>
            <th>Invoice Number</th>
            <th>Total Ordered Products</th>
            <th>Ordered Products</th>
            <th> Date Ordered</th>
            <th>Action</th>
            <th>Order Status</th>
          </tr>
        </thead>
        <tbody class='table-dark text-light' id="orders-container">
         <?php
         $num = 0;
         while ($row_data = mysqli_fetch_assoc($exe_query)) {
           $order_id = $row_data['order_id'];
           $user_id = $row_data['user_id'];
           $amount_due = $row_data['amount_due'];
           $invoice_number = $row_data['invoice_number'];

           $check_refund = "SELECT * FROM canceled_orders WHERE invoice_number = $invoice_number AND refund = 'yes'";
           $exe = mysqli_query($conn, $check_refund);
           $refunded = 0;
           if (mysqli_num_rows($exe) > 0) {
             $row = mysqli_fetch_assoc($exe);
             $refunded = $row['refund'];
           }

           $total_products = $row_data['total_products'];
           $order_date = $row_data['order_date'];
           $timestamp_o = strtotime($order_date); // Convert to timestamp
           $formatted_date_o = date('d-M-y H:i:s', $timestamp_o);
           $payment_mode = $row_data['payment_mode'];
           $contact_number = $row_data['contact_number'];
           $contact_email = $row_data['contact_email'];
           $payment_mode = $row_data['payment_mode'];
           $address = $row_data['address'];
           $order_status = $row_data['order_status'];
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
             <td><button type='button' name='view_orders_btn' class='btn btn-secondary p-2 action-btn' data-order-id="<?php echo $order_id; ?>" data-invoice-number="<?php echo $invoice_number; ?>" data-order-status="<?php echo $order_status; ?>" data-payment-mode="<?php echo $payment_mode; ?>" data-refund=<?php echo $refunded ?>> Action
             </button></td>
             <td><?php echo $order_status; ?></td>
           </tr>

           <div class="modal fade" id="<?php echo $invoice_number; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel"><?php echo 'Invoice Number: ' . $invoice_number ?></h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">
                     <div class="row bg-secondary">
                       <div class="col-3">
                         <span class="header"></span>
                       </div>
                       <div class="col-4">
                         <span class="header">Product Name</span>
                       </div>
                       <div class="col-1">
                         <span class="header">Qty</span>
                       </div>
                       <div class="col-2">
                         <span class="header">Product Price</span>
                       </div>
                       <div class="col-2">
                         <span class="header">Subtotal</span>
                       </div>
                     </div>
                     <?php
                       $view_order_details = "SELECT * FROM `view_order_details` WHERE invoice_number='$invoice_number'";
                       $result = mysqli_query($conn,$view_order_details); //execute sql command
                       $result_count = mysqli_num_rows($result); // count rows
                       $total_price = 0;
                       if ($result_count > 0) {
                         // fetch product Details
                         while ($row = mysqli_fetch_array($result)) {
                           $product_id = $row['product_id'];
                           $quantity = $row['quantity'];
                           $product_price = $row['price'];
                           $subtotal = $row['subtotal'];
                           $fetch_product_from_product_table = "SELECT * FROM `products` WHERE product_id='$product_id'";
                           $result_product = mysqli_query($conn,$fetch_product_from_product_table);
                           while ($row_product = mysqli_fetch_array($result_product)) {
                             $product_title = $row_product['product_title'];
                             $product_image = $row_product['product_image1'];
                             echo "<div class='row mb-3 display_products'>
                                     <div class='col-3'>
                                       <img src='../product_imgs/$product_image' alt='product_image' class='display_img'>
                                     </div>
                                     <div class='col-4'>
                                       <span>$product_title</span>
                                     </div>
                                     <div class='col-1'>
                                       <span>$quantity</span>
                                     </div>
                                     <div class='col-2'>
                                       <span>$product_price/-</span>
                                     </div>
                                     <div class='col-2'>
                                       <span>$subtotal/-</span>
                                     </div>
                                   </div>";
                           $total_price += $subtotal;
                           }
                         }
                       }
                      ?>
                      <div class="row bg-secondary text-light">
                        <div class="col-6">
                          <span>Total Price : </span>
                        </div>
                        <div class="col-6">
                          <span><?php echo 'Kshs.' . $total_price; ?></span>
                        </div>
                      </div>
                   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   </div>
                 </div>
               </div>
           </div>
          <?php }?>
          </tbody>
          <?php }?>

      </table>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <script type="text/javascript">

      emailjs.init("YOUR_PUBLIC_KEY");
      document.addEventListener('DOMContentLoaded', function (){
        document.body.addEventListener('click', function (event) {
          if (event.target.classList.contains('action-btn')){
            const button = event.target;
            const orderId = button.getAttribute('data-order-id');
            const invoiceNumber = button.getAttribute('data-invoice-number');
            const orderStatus = button.getAttribute('data-order-status');
            const payment_mode = button.getAttribute('data-payment-mode');
            const refunded = button.getAttribute('data-refund');

            // Fetch order details dynamically via AJAX
            fetch(`../functions/fetch_order_details.php?invoice_number=${encodeURIComponent(invoiceNumber)}`)
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  const userDetails = data.order_user_details;

                  // Generate dynamic select options based on order status
                  let options = '';
                  if (orderStatus === 'pending') {
                    options = `
                      <option value="processing">Processing Order</option>
                      <option value="cancel">Cancel Order</option>
                      <option value="download">Download Invoice</option>
                    `;
                  } else if (orderStatus === 'processing') {
                    options = `
                      <option value="dispatched">Dispatch Order</option>
                      <option value="cancel">Cancel Order</option>
                      <option value="download">Download Invoice</option>
                    `;
                  } else if (orderStatus === 'dispatched') {
                    options = `
                      <option value="cancel">Cancel Order</option>
                      <option value="download">Download Invoice</option>
                    `;
                  } else if (orderStatus === 'delivered') {
                    options = `
                      <option value="complete">Complete Order</option>
                      <option value="cancel" disabled>Cancel Order</option>
                      <option value="download">Download Invoice</option>
                    `;
                  } else if (orderStatus === 'cancelled' && payment_mode === 'pay_on_delivery') {
                    options = `
                      <option value="cancellation_reason">View Cancelation Reason</option>
                      <option value="download">Download Invoice</option>
                    `;
                  } else if (orderStatus === 'complete') {
                    options = `
                      <option value="download">Download Invoice</option>
                    `;
                  } else if (payment_mode === 'pay_with_mpesa' && orderStatus === 'cancelled' && refunded !== 'yes') {
                    options = `
                      <option value="cancellation_reason">View Cancelation Reason</option>
                      <option value="refund">Initiate Refund</option>
                      <option value="download">Download Invoice</option>
                    `;
                  } else {
                    options = `
                      <option value="cancellation_reason">View Cancelation Reason</option>
                      <option value="download">Download Invoice</option>
                    `;
                  }

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
                    const day = String(date.getDate()).padStart(2, '0'); // Day with leading zeros
                    const monthShort = date.toLocaleString('en-US', { month: 'short' }); // Short month name
                    const year = String(date.getFullYear()).slice(-2); // Last 2 digits of the year
                    const hours = String(date.getHours()).padStart(2, '0'); // Hours with leading zeros
                    const minutes = String(date.getMinutes()).padStart(2, '0'); // Minutes with leading zeros
                    const seconds = String(date.getSeconds()).padStart(2, '0'); // Seconds with leading zeros

                    // Format as "16-Dec-24 00:00:00"
                    return `${day}-${monthShort}-${year} ${hours}:${minutes}:${seconds}`;
                  }

                  // Example usage
                  let expectedDate = userDetails.expected_date;
                  let formattedDate = formatDateString(expectedDate);

                  let delivered_date = userDetails.delivered_date;
                  // if (delivered_date == 'null' && orderStatus !== 'cancel') {
                  //   let formatted_delDate = 'Awaiting Delivery'
                  // }
                  // else {
                    let formatted_delDate = formatDateString(delivered_date);
                  // }
                  let contactEmail = userDetails.contact_email;
                  let userName = userDetails.user_name;

                  // Show Swal modal with details
                  Swal.fire({
                    title: `Order Action: ${invoiceNumber}`,
                    html: `
                      <h5 style='color: rgb(13 202 240)'>User Info</h5>
                      <div style='display: flex; flex-wrap: wrap; justify-content: space-around;'>
                        <p><b>Full Name:</b> ${userDetails.full_name}</p>
                        <p><b>User_Name:</b> ${userDetails.user_name}</p>
                        <p><b>Email:</b> ${userDetails.user_email}</p>
                        <p><b>Acc Phone No.:</b> ${userDetails.user_mobile}</p>
                      </div>
                      <hr>
                      <h5 style='color: rgb(13 202 240)'>Order Info</h5>
                      <p><b>Pickup Address:</b> ${userDetails.order_address}</p>
                      <p><b>Contact Phone No.:</b> ${userDetails.contact_number}</p>
                      <p><b>Contact Email:</b> ${contactEmail}</p>
                      <p><b>Expected Date:</b> ${formattedDate}</p>
                      <p><b>Delivered Date:</b> ${delivered_date}</p>
                      <p><b>Payment Mode:</b> ${paymentMode}</p>
                      <select id="order-action-select" class="swal2-select">
                          <option value="" selected disabled>Select Action</option>
                          ${options}
                      </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Proceed',
                    preConfirm: () => {
                      const selectedAction = document.getElementById('order-action-select').value;
                      if (!selectedAction) {
                        Swal.showValidationMessage('Please select an action.');
                        return false;
                      }
                      return selectedAction;
                    }
                  }).then(result => {
                    if (result.isConfirmed) {
                      const selectedAction = result.value;

                      if (selectedAction === 'cancel') {
                        Swal.fire({
                          title: 'Cancel Order',
                          html: `
                            <textarea id="cancel-reason" placeholder="Enter cancellation reason" class="swal2-textarea" data-user="admin"></textarea>
                          `,
                          showCancelButton: true,
                          confirmButtonText: 'Cancel Order',
                          preConfirm: () => {
                            const reason = document.getElementById('cancel-reason').value;
                            const user = document.getElementById('cancel-reason').getAttribute('data-user');
                            if (!reason) {
                              Swal.showValidationMessage('Please provide a reason for cancellation.');
                            }
                            return { reason, user };
                          }
                        }).then(cancelResult => {
                          if (cancelResult.isConfirmed) {
                            const { reason, user } = cancelResult.value;

                            // Show loading modal
                            Swal.fire({
                              title: '',
                              text: 'Canceling Order...',
                              allowOutsideClick: false,
                              didOpen: () => {
                                Swal.showLoading(); // Display loading spinner
                              },
                            });

                            setTimeout(() => {
                              fetch('../functions/cancel_order.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ invoiceNumber, reason, user }) // Include the user attribute
                              }).then(response => response.json())
                                .then(data => {
                                  if (data.success) {
                                    // Send email notification using EmailJS
                                    emailjs.send("YOUR_SERVICE_ID", "YOUR_TEMPLATE_ID", {
                                      from_name: "JamboShop",
                                      to_name: userName, // Customer's name
                                      message: `Your order with Invoice Number: ${invoiceNumber} has been canceled. Reason: ${reason}. If already paid for the order and/or if you have any concerns, please contact our support.`,
                                      to_email: contactEmail, // Customer's email address
                                    }).then(function (emailResponse) {
                                      console.log("Email Sent Successfully!", emailResponse.status, emailResponse.text);
                                      Swal.fire({
                                        title: 'Success',
                                        text: `${data.message} An email notification has been sent to the customer.`,
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                      }).then(() => {
                                        // Reload the page after clicking OK
                                        location.reload();
                                      });
                                    }).catch(function (emailError) {
                                      console.error("Failed to Send Email...", emailError);
                                      Swal.fire({
                                        title: 'Success',
                                        text: `${data.message} However, the email notification could not be sent.`,
                                        icon: 'warning',
                                        confirmButtonText: 'OK',
                                      }).then(() => {
                                        location.reload();
                                      });
                                    });
                                  } else {
                                    Swal.fire({
                                      title: 'Error',
                                      text: data.message,
                                      icon: 'error',
                                      confirmButtonText: 'OK',
                                    });
                                  }
                                })
                                .catch(error => {
                                  console.error('Error:', error);
                                  Swal.fire({
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                });
                              });
                            }, 5000); // Wait for 5 seconds before proceeding
                          }
                        });
                      } else if (selectedAction === 'download') {
                        // Download invoice
                        window.location.href = `../functions/download_invoice.php?invoice=${invoiceNumber}`;
                      } else if (selectedAction === 'refund') {
                        Swal.fire({
                          title: '',
                          text: 'Initiating refund...',
                          allowOutsideClick: false,
                          didOpen: () => {
                            Swal.showLoading();
                          },
                        });

                        setTimeout(() => {
                          fetch('../functions/refund.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ invoiceNumber}) // Include the user attribute
                          }).then(response => response.json())
                            .then(data => {
                              if (data.success) {
                                Swal.fire({
                                  title: 'Success',
                                  text: data.message,
                                  icon: 'success',
                                  confirmButtonText: 'OK',
                                }).then(() => {
                                  // Reload the page after clicking OK
                                  location.reload();
                                });
                              } else {
                                Swal.fire({
                                  title: 'Error',
                                  text: data.message,
                                  icon: 'error',
                                  confirmButtonText: 'OK',
                                });
                              }
                          })
                          .catch(error => {
                              console.error('Error:', error);
                              Swal.fire({
                                title: 'Error',
                                text: 'Something went wrong. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                              });
                          });
                        }, 5000);

                      } else {
                        Swal.fire({
                          title: '',
                          text: 'Updating Order Status...',
                          allowOutsideClick: false,
                          didOpen: () => {
                            Swal.showLoading();
                          },
                        });

                        // Simulate delay (e.g., 5 seconds) before sending the update request
                        setTimeout(() => {
                          fetch('../functions/update_order_status.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ contactEmail, invoiceNumber, action: selectedAction }),
                          })
                          .then(response => response.json())
                          .then(data => {
                            if (data.success) {
                              // Define the default message
                              let emailMessage = `Your order status of invoice no.: ${invoiceNumber} has been updated to ${selectedAction}. You will be contacted once the order reaches its destination.`;

                              // If the order is 'complete', modify the message
                              if (selectedAction === 'complete') {
                                emailMessage = `Your order with invoice no.: ${invoiceNumber} has been successfully completed. We’d appreciate it if you could take a moment to share your experience with us. Your feedback helps us serve you better!.`;
                              }
                              // Call EmailJS to send the email notification
                              emailjs.send("YOUR_SERVICE_ID", "YOUR_TEMPLATE_ID", {
                                from_name: "JamboShop",
                                to_name: userName,
                                message: emailMessage, // dynamic message
                                to_email: contactEmail, // Customer's email address
                              }).then(function(response) {
                                console.log("Email Sent Successfully!", response.status, response.text); //debugging
                                handleResponse(data, 'Order Status Updated Successfully');
                              }).catch(function(error) {
                                console.error("Failed to Send Email...", error); //debugging
                                Swal.fire('Order Status Updated', 'But email notification failed to send.', 'warning');
                              });
                            } else {
                              handleResponse(data, 'Failed to Update Order Status');
                            }
                          })
                          .catch(handleError);
                        }, 5000);
                      }

                    }
                  });
                } else {
                  Swal.fire('Error', data.message, 'error');
                }
              })
              .catch(error => {
                console.error('Error fetching order details:', error);
                Swal.fire('Error', 'Failed to fetch order details.', 'error');
              });

          }

        })
      })

      document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function () {
          const orderId = this.getAttribute('data-order-id');
          const invoiceNumber = this.getAttribute('data-invoice-number');
          const orderStatus = this.getAttribute('data-order-status');

          // Fetch order details dynamically via AJAX
          fetch(`../functions/fetch_order_details.php?invoice_number=${encodeURIComponent(invoiceNumber)}`)
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                const userDetails = data.order_user_details;

                // Generate dynamic select options based on order status
                let options = '';
                if (orderStatus === 'pending') {
                  options = `
                    <option value="processing">Processing Order</option>
                    <option value="cancel">Cancel Order</option>
                    <option value="download">Download Invoice</option>
                  `;
                } else if (orderStatus === 'processing') {
                  options = `
                    <option value="dispatched">Dispatch Order</option>
                    <option value="cancel">Cancel Order</option>
                    <option value="download">Download Invoice</option>
                  `;
                } else if (orderStatus === 'dispatched') {
                  options = `
                    <option value="cancel">Cancel Order</option>
                    <option value="download">Download Invoice</option>
                  `;
                } else if (orderStatus === 'delivered') {
                  options = `
                    <option value="complete">Complete Order</option>
                    <option value="cancel" disabled>Cancel Order</option>
                    <option value="download">Download Invoice</option>
                  `;
                } else if (orderStatus === 'cancelled') {
                  options = `
                    <option value="download">View Cancelation Reason</option>
                    <option value="download">Download Invoice</option>
                  `;
                } else if (orderStatus === 'complete') {
                  options = `
                    <option value="download">Download Invoice</option>
                  `;
                }

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
                  const day = String(date.getDate()).padStart(2, '0'); // Day with leading zeros
                  const monthShort = date.toLocaleString('en-US', { month: 'short' }); // Short month name
                  const year = String(date.getFullYear()).slice(-2); // Last 2 digits of the year
                  const hours = String(date.getHours()).padStart(2, '0'); // Hours with leading zeros
                  const minutes = String(date.getMinutes()).padStart(2, '0'); // Minutes with leading zeros
                  const seconds = String(date.getSeconds()).padStart(2, '0'); // Seconds with leading zeros

                  // Format as "16-Dec-24 00:00:00"
                  return `${day}-${monthShort}-${year} ${hours}:${minutes}:${seconds}`;
                }

                // Example usage
                let expectedDate = userDetails.expected_date;
                let formattedDate = formatDateString(expectedDate);

                let delivered_date = userDetails.delivered_date;
                // if (delivered_date == 'null' && orderStatus !== 'cancel') {
                //   let formatted_delDate = 'Awaiting Delivery'
                // }
                // else {
                  let formatted_delDate = formatDateString(delivered_date);
                // }

                // Show Swal modal with details
                Swal.fire({
                  title: `Order Action: ${invoiceNumber}`,
                  html: `
                    <h5 style='color: rgb(13 202 240)'>User Info</h5>
                    <div style='display: flex; flex-wrap: wrap; justify-content: space-around;'>
                      <p><b>Full Name:</b> ${userDetails.full_name}</p>
                      <p><b>User_Name:</b> ${userDetails.user_name}</p>
                      <p><b>Email:</b> ${userDetails.user_email}</p>
                      <p><b>Acc Phone No.:</b> ${userDetails.user_mobile}</p>
                    </div>
                    <hr>
                    <h5 style='color: rgb(13 202 240)'>Order Info</h5>
                    <p><b>Pickup Address:</b> ${userDetails.order_address}</p>
                    <p><b>Expected Date:</b> ${formattedDate}</p>
                    <p><b>Delivered Date:</b> ${delivered_date}</p>
                    <p><b>Payment Mode:</b> ${paymentMode}</p>
                    <select id="order-action-select" class="swal2-select">
                        <option value="" selected disabled>Select Action</option>
                        ${options}
                    </select>
                  `,
                  showCancelButton: true,
                  confirmButtonText: 'Proceed',
                  preConfirm: () => {
                    const selectedAction = document.getElementById('order-action-select').value;
                    if (!selectedAction) {
                      Swal.showValidationMessage('Please select an action.');
                      return false;
                    }
                    return selectedAction;
                  }
                }).then(result => {
                  if (result.isConfirmed) {
                    const selectedAction = result.value;

                    if (selectedAction === 'cancel') {
                        // Show textarea for cancellation reason
                        Swal.fire({
                          title: 'Cancel Order',
                          html: `
                            <textarea id="cancel-reason" placeholder="Enter cancellation reason" class="swal2-textarea"></textarea>
                          `,
                          showCancelButton: true,
                          confirmButtonText: 'Cancel Order',
                          preConfirm: () => {
                            const reason = document.getElementById('cancel-reason').value;
                            if (!reason) {
                              Swal.showValidationMessage('Please provide a reason for cancellation.');
                            }
                            return reason;
                          }
                        }).then(cancelResult => {
                          if (cancelResult.isConfirmed) {
                            // Send cancellation request to server
                            const reason = cancelResult.value;

                            // Show loading modal
                            Swal.fire({
                                title: '',
                                text: 'Canceling Order...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                  Swal.showLoading(); // Display loading spinner
                                },
                            });
                            // Simulate delay (e.g., 5 seconds) before sending the cancellation request
                            setTimeout(() => {
                              fetch('../functions/cancel_order.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ invoiceNumber, reason })
                              }).then(response => response.json())
                                .then(data => {
                                  if (data.success) {
                                    Swal.fire({
                                      title: 'Success',
                                      text: data.message,
                                      icon: 'success',
                                      confirmButtonText: 'OK',
                                    }).then(() => {
                                      // Reload the page after clicking OK
                                      location.reload();
                                    });
                                  } else {
                                    Swal.fire({
                                      title: 'Error',
                                      text: data.message,
                                      icon: 'error',
                                      confirmButtonText: 'OK',
                                    });
                                  }
                              })
                              .catch(error => {
                                  console.error('Error:', error);
                                  Swal.fire({
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                  });
                              });
                            }, 2000);
                          }
                        });
                    } else if (selectedAction === 'download') {
                      // Download invoice
                      window.location.href = `../functions/download_invoice.php?invoice=${invoiceNumber}`;
                    } else {
                      Swal.fire({
                        title: '',
                        text: 'Updating Order Status...',
                        allowOutsideClick: false,
                        didOpen: () => {
                          Swal.showLoading();
                        },
                      });

                      // Simulate delay (e.g., 5 seconds) before sending the update request
                      setTimeout(() => {
                        fetch('../functions/update_order_status.php', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json' },
                          body: JSON.stringify({ invoiceNumber, action: selectedAction }),
                        })
                        .then(response => response.json())
                        .then(data => handleResponse(data, 'Order Status Updated Successfully'))
                        .catch(handleError);
                      }, 5000);
                    }

                  }
                });
              } else {
                Swal.fire('Error', data.message, 'error');
              }
            })
            .catch(error => {
              console.error('Error fetching order details:', error);
              Swal.fire('Error', 'Failed to fetch order details.', 'error');
            });
          });
      });

      // Helper function to handle success responses
      function handleResponse(data, successMessage) {
        if (data.success) {
          Swal.fire({
            title: 'Success',
            text: successMessage,
            icon: 'success',
            confirmButtonText: 'OK',
          }).then(() => {
            location.reload(); // Reload the page to reflect changes
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: data.message,
            icon: 'error',
            confirmButtonText: 'OK',
          });
        }
      }

      // Helper function to handle errors
      function handleError(error) {
        console.error('Error:', error);
        Swal.fire({
          title: 'Error',
          text: 'Something went wrong. Please try again later.',
          icon: 'error',
          confirmButtonText: 'OK',
        });
      }

      // FILTER FUNCTIONALITY
      $(document).ready(function () {
        function fetchFilteredOrders() {
          const selectedStatus = $('input[name="order_status"]:checked').val();
          const selectedDateRange = $('input[name="date_range"]:checked').val();
          const invoiceNumber = <?php echo json_encode($invoice_number); ?>

          // Send AJAX request
          $.ajax({
            url: '../functions/filter_orders.php',
            method: 'POST',
            data: {
              user_role: 'admin',
              invoice_number: invoiceNumber,
              status: selectedStatus,
              date_range: selectedDateRange
            },
            success: function (response) {
              $('#orders-container').html(response);
            },
            error: function () {
              alert('Failed to fetch orders. Please try again.');
            }
          });
        }

        // Trigger filtering when filters are changed
        $('.filter-status, .filter-date').on('change', function () {
          fetchFilteredOrders();
        });
      });
    </script>
