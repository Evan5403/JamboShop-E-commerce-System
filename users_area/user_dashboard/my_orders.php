<!-- order details -->
<style media="screen">

.swal2-popup {
  width: 80%; /* Increase modal width */
}
</style>

<div class="details-myorder">
  <div class="recentOrders">
      <div class="cardHeader">
        <h2>All Orders</h2>
        <!-- <a href="#" class="btn">View All</a> -->
        <div class="filter-options">
          <div class="item-sortir">
            <div class="label">
              <span class="mobile-hide">Filter by Order Status</span>
              <i class="ri-arrow-down-s-line"></i>
            </div>
            <ul>
              <li><input type="radio" name="date_range" class="filter-date" value="All"> Default</li>
              <li><input type="radio" name="order_status" class="filter-status" value="pending">Pending</li>
              <li><input type="radio" name="order_status" class="filter-status" value="processing">Processing</li>
              <li><input type="radio" name="order_status" class="filter-status" value="dispatched">Dispatched</li>
              <li><input type="radio" name="order_status" class="filter-status" value="delivered">Delivered</li>
              <li><input type="radio" name="order_status" class="filter-status" value="complete">Complete</li>
              <li><input type="radio" name="order_status" class="filter-status" value="cancelled">Cancelled</li>
            </ul>
          </div>
          <div class="item-sortir">
            <div class="label">
              <span class="mobile-hide">Filter by Date Range</span>
              <i class="ri-arrow-down-s-line"></i>
            </div>
            <ul>
              <li><input type="radio" name="date_range" class="filter-date" value="All"> Default</li>
              <li><input type="radio" name="date_range" class="filter-date" value="Today">Today</li>
              <li><input type="radio" name="date_range" class="filter-date" value="Yesterday">Yesterday</li>
              <li><input type="radio" name="date_range" class="filter-date" value="Last 7 Days">Last 7 Days</li>
              <li><input type="radio" name="date_range" class="filter-date" value="Last Month">Last Month</li>
            </ul>
          </div>
        </div>
      </div>
      <?php
        $username = $_SESSION['username'];
        $get_user_deatils = "SELECT * FROM `user_table` WHERE user_name='$username'";
        $result_query = mysqli_query($conn, $get_user_deatils);
        $row_query = mysqli_fetch_array($result_query);
        $user_id = $row_query['user_id'];
        $get_orders = "SELECT * FROM `user_orders` WHERE user_id='$user_id' ORDER BY order_date DESC";
        $result_orders = mysqli_query($conn,$get_orders);
        $row_count = mysqli_num_rows($result_orders);
        if ($row_count == 0) {
          echo "<h2 style='color: red; text-align: center;'> You have not ordered anything yet";
        } else { ?>
          <table>
              <thead>
                  <tr>
                    <td>Invoice Number</td>
                    <td>Total Ordered Products</td>
                    <td>View Order Details</td>
                    <td>Amount Due</td>
                    <td>Payment</td>
                    <td>Order Date</td>
                    <td>Expected Date</td>
                    <td>Action</td>
                    <td>Status</td>
                    <td>Order Review</td>
                  </tr>
              </thead>

              <tbody id="orders-container">
                <?php
                 while ($row_order = mysqli_fetch_assoc($result_orders)) {
                   $order_id = $row_order['order_id'];
                   $amount_due = $row_order['amount_due'];
                   $total_products = $row_order['total_products'];
                   $order_date = $row_order['order_date'];
                   $expected_date = $row_order['expected_date'];
                   $timestamp_o = strtotime($order_date); // Convert to timestamp
                   $formatted_date_o = date('d-M-y H:i:s', $timestamp_o);
                   $timestamp_e = strtotime($expected_date); // Convert to timestamp
                   $formatted_date_e = date('d-M-y H:i:s', $timestamp_e);
                   $payment_mode = $row_order['payment_mode'];
                   $invoice_number = $row_order['invoice_number'];
                   $order_status = $row_order['order_status'];
                   $order_feedback = $row_order['order_feedback'];
                   $feedback_type = $row_order['feedback_type'];
                     ?>
                     <tr>
                       <td><?php echo $invoice_number ?></td>
                       <td><?php echo $total_products ?></td>
                       <td><button class="btn view-details-btn" data-invoice="<?php echo $invoice_number; ?>" data-order-status="<?php echo $order_status; ?>">Order Details</button></td>
                       <td>Kshs.<?php echo $amount_due ?></td>
                       <td><?php
                         if ($payment_mode == 'pay_on_delivery' && $order_status !== 'complete') {
                           echo "Pay On Delivery";
                         }elseif ($payment_mode == 'pay_with_mpesa') {
                           echo "Paid Via Mpesa";
                         }else {
                           echo "Paid";
                         }
                       ?></td>
                       <td><?php echo $formatted_date_o ?></td>
                       <td><?php echo $formatted_date_e ?></td>
                       <td><button class="btn action-btn"
                                   data-invoice="<?php echo $invoice_number; ?>"
                                   data-status="<?php echo $order_status; ?>">Action
                       </button></td>
                       <td>
                        <span
                            class="status <?php echo $order_status ?>"
                            data-invoice_number="<?php echo $invoice_number; ?>"
                            <?php if ($order_status === 'cancelled'): ?>
                                data-tooltip="<?php
                                    // Fetch the cancellation reason from the database
                                    $cancel_reason_query = "SELECT * FROM canceled_orders WHERE invoice_number='$invoice_number'";
                                    $result = mysqli_query($conn, $cancel_reason_query);
                                    $row = mysqli_fetch_assoc($result);
                                    $refund = $row['refund'];
                                    $reason = htmlspecialchars($row['cancel_reason'] ?? 'No reason provided', ENT_QUOTES, 'UTF-8');
                                    $cancelledBy = htmlspecialchars($row['canceled_by'] ?? 'Uknown', ENT_QUOTES, 'UTF-8');
                                    $notice = "";
                                    if ($refund !== 'yes' && $payment_mode == 'pay_with_mpesa') {
                                      $notice = "Please visit our shop or contact us to initiate your refund";
                                    } elseif ($payment_mode == 'pay_with_mpesa') {
                                      $notice = "Already refunded";
                                    }
                                    // Construct the tooltip content
                                    echo "Cancellation Reason-> $reason:\nCancelled By-> $cancelledBy :\n $notice";
                                ?>"
                            <?php endif; ?>
                        >
                            <?php echo $order_status; ?>
                        </span>
                      </td>
                       <td><?php
                        if ($order_feedback == '') {
                          echo "<b> - Add your order review by clicking the 'Action button'</b>";
                        }
                        echo $order_feedback. ' - ' ."<span class='feedback_type $feedback_type'>$feedback_type</span>";
                       ?></td>
                     </tr>
                 <?php } ?>
              </tbody>
          </table>
      <?php  }
       ?>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (event) {
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

      if (event.target.classList.contains('action-btn')){
        const button = event.target;
        const invoiceNumber = button.getAttribute('data-invoice');
        const orderStatus = button.getAttribute('data-status');
        const recieve_op = !(orderStatus === 'dispatched')
        const isComplete = !(orderStatus === 'delivered' || orderStatus === 'complete' || orderStatus === 'cancelled');
        let contactEmail = 'null';

        Swal.fire({
          title: `Actions for Invoice #${invoiceNumber}`,
          html: `
            <select id="action-select" class="swal2-select">
              <option value="" selected disabled>Select Action</option>
              <option value="cancel" ${!isComplete ? "disabled" : ""}>Cancel Order</option>
              <option value="delivered" ${recieve_op ? "disabled" : ""}>Recieved Order</option>
              <option value="download">Download Invoice</option>
              <option value="feedback" ${isComplete ? "disabled" : ""}>Order Feedback</option>
            </select>
            <div id="additional-content"></div>
          `,
          showCancelButton: true,
          confirmButtonText: 'Proceed',
          preConfirm: () => {
            const selectedAction = document.getElementById('action-select').value;
            if (!selectedAction) {
              Swal.showValidationMessage('Please select an action.');
            }
            return selectedAction;
          }
        }).then(result => {
          if (result.isConfirmed) {
            const action = result.value;
            const additionalContent = document.getElementById('additional-content');

            if (action === 'cancel') {
              Swal.fire({
                title: 'Cancel Order',
                html: `
                  <textarea id="cancel-reason" placeholder="Enter cancellation reason" class="swal2-textarea" data-user="user"></textarea>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cancel Order',
                preConfirm: () => {
                  const reason = document.getElementById('cancel-reason').value;
                  const user = document.getElementById('cancel-reason').getAttribute('data-user');
                  if (!reason) {
                    Swal.showValidationMessage('Please provide a reason for cancellation.');
                  }
                  return { reason, user , contactEmail};
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
                    fetch('../../functions/cancel_order.php', {
                      method: 'POST',
                      headers: { 'Content-Type': 'application/json' },
                      body: JSON.stringify({ invoiceNumber, reason, user }) // Include the user attribute
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
                  }, 5000); // Wait for 5 seconds before proceeding
                }
              });
            } else if (action === 'download') {
              window.location.href = `../../functions/download_invoice.php?invoice=${invoiceNumber}`;
            } else if (action === 'feedback') {
              Swal.fire({
                title: 'Order Feedback',
                html: `
                  <textarea id="feedback-text" placeholder="Enter your feedback" class="swal2-textarea"></textarea> <br>
                  <input type="radio" name="feedback_type" id="pos-feedback" value="positive" class="swal2-radio">
                  <label for="pos-feedback">positive</label>
                  <input type="radio" name="feedback_type" id="neg-feedback" value="negative" class="swal2-radio">
                  <label for="neg-feedback">negative</label>
                `,
                showCancelButton: true,
                confirmButtonText: 'Submit Feedback',
                preConfirm: () => {
                  const feedback = document.getElementById('feedback-text').value;
                  const feedbackType = document.querySelector('input[name="feedback_type"]:checked');
                  if (!feedback || !feedbackType) {
                    Swal.showValidationMessage('Please provide your feedback.');
                    return false;
                  }
                  return {
                    feedback: feedback,
                    feedbackType: feedbackType.value
                  };
                }
              }).then(feedbackResult => {
                if (feedbackResult.isConfirmed) {
                  // Extract feedback and feedbackType from the result
                  const feedback = feedbackResult.value.feedback;
                  const feedbackType = feedbackResult.value.feedbackType;

                  // Send feedback to server
                  fetch('../../functions/order_feedback.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ invoiceNumber, feedback, feedbackType }) // Include feedbackType
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
                }
              });
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
                fetch('../../functions/update_order_status.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ invoiceNumber, action: action , contactEmail}),
                })
                .then(response => response.json())
                .then(data => handleResponse(data, 'Order Status Updated Successfully'))
                .catch(handleError);
              }, 2000);
            }
          }
        });
      }

    });
  });

  document.querySelectorAll('.action-btn').forEach(button => {
    button.addEventListener('click', function() {
      const invoiceNumber = this.getAttribute('data-invoice');
      const orderStatus = this.getAttribute('data-status');
      const recieve_op = !(orderStatus === 'dispatched')
      const isComplete = !(orderStatus === 'delivered' || orderStatus === 'complete' || orderStatus === 'cancelled');

      Swal.fire({
        title: `Actions for Invoice #${invoiceNumber}`,
        html: `
          <select id="action-select" class="swal2-select">
            <option value="" selected disabled>Select Action</option>
            <option value="cancel" ${!isComplete ? "disabled" : ""}>Cancel Order</option>
            <option value="delivered" ${recieve_op ? "disabled" : ""}>Recieved Order</option>
            <option value="download">Download Invoice</option>
            <option value="feedback" ${isComplete ? "disabled" : ""}>Order Feedback</option>
          </select>
          <div id="additional-content"></div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        preConfirm: () => {
          const selectedAction = document.getElementById('action-select').value;
          if (!selectedAction) {
            Swal.showValidationMessage('Please select an action.');
          }
          return selectedAction;
        }
      }).then(result => {
        if (result.isConfirmed) {
          const action = result.value;
          const additionalContent = document.getElementById('additional-content');

          if (action === 'cancel') {
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

                setTimeout(() => {
                  fetch('../../functions/cancel_order.php', {
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
                }, 5000); // Wait for 5 seconds before proceeding
              }
            });
          } else if (action === 'download') {
            window.location.href = `../../functions/download_invoice.php?invoice=${invoiceNumber}`;
          } else if (action === 'feedback') {
            Swal.fire({
              title: 'Order Feedback',
              html: `
                <textarea id="feedback-text" placeholder="Enter your feedback" class="swal2-textarea"></textarea>
              `,
              showCancelButton: true,
              confirmButtonText: 'Submit Feedback',
              preConfirm: () => {
                const feedback = document.getElementById('feedback-text').value;
                if (!feedback) {
                  Swal.showValidationMessage('Please provide your feedback.');
                }
                return feedback;
              }
            }).then(feedbackResult => {
              if (feedbackResult.isConfirmed) {
                // Send feedback to server
                const feedback = feedbackResult.value;
                fetch('../../functions/order_feedback.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ invoiceNumber, feedback })
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
              }
            });
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
              fetch('../../functions/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ invoiceNumber, action: action }),
              })
              .then(response => response.json())
              .then(data => handleResponse(data, 'Order Status Updated Successfully'))
              .catch(handleError);
            }, 2000);
          }

        }
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
      const username = <?php echo json_encode($_SESSION['username']); ?>

      // Send AJAX request
      $.ajax({
        url: '../../functions/filter_orders.php',
        method: 'POST',
        data: {
          user: username,
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
