<?php
  if ($role !== 'admin' AND $role !== 'store_manager') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
  }

  $get_feedbacks = "SELECT
                    user_orders.user_id,
                    user_orders.invoice_number,
                    user_orders.order_status,
                    user_orders.order_feedback,
                    user_orders.feedback_type,
                    user_table.user_name,
                    user_table.user_image
                  FROM
                    user_orders
                  INNER JOIN
                    user_table
                  ON
                    user_orders.user_id = user_table.user_id
                  WHERE
                    user_orders.feedback_type != ''";
  $result_feedbacks = mysqli_query($conn, $get_feedbacks);
  $feedback_num_rows = mysqli_num_rows($result_feedbacks);

 ?>

<div class="header">
    <div class="left">
        <h1>Order Feedbacks</h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                    Dashboard
                  </a></li>
              /
              <li><a href="#" class="active">Feedbacks</a></li>
          <?php } else { ?>
                  <li><a href="admin_profile.php?store_manager">
                        Dashboard
                      </a></li>
                  /
                  <li><a href="#" class="active">Feedbacks</a></li>
          <?php } ?>
        </ul>
    </div>
</div>

<div class="bottom-data">
    <div class="orders">
        <div class="header">
            <i class='bx bx-receipt'></i>
            <h3>User/Admin Feedbacks And Cancellation Reasons On Orders</h3>
            <!-- <i class='bx bx-filter'></i>
            <i class='bx bx-search'></i> -->
        </div>
        <table>
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Invoice No.</th>
                    <th>Order Status</th>
                    <th>Feedback Type</th>
                    <th>Feedback Text</th>
                </tr>
            </thead>
            <tbody>
              <?php
               if ($result_feedbacks && $feedback_num_rows > 0) {
                 while ($row = mysqli_fetch_assoc($result_feedbacks)) {
                   $feedback_type = $row['feedback_type'];
                   $order_status = $row['order_status'];
                   ?>
                   <tr>
                       <td>
                           <img src="../../users_area/user_images/<?php echo $row['user_image'] ?>">
                           <p><?php echo $row['user_name'] ?></p>
                       </td>
                       <td><?php echo $row['invoice_number'] ?></td>
                       <td><span class="status <?php echo $order_status ?>"><?php echo $order_status ?></span></td>
                       <td><span class="feedback_type <?php echo $feedback_type ?>"><?php echo $feedback_type ?></span></td>
                       <td><?php echo $row['order_feedback'] ?></td>
                   </tr>
               <?php }} ?>
            </tbody>
        </table>
    </div>

</div>
