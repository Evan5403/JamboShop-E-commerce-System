<?php
  if ($role !== 'store_manager') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
  }

  // Query to fetch dashboard metrics
  $query = "
            SELECT
              COUNT(DISTINCT uo.order_id) AS total_orders,
              COUNT(CASE WHEN uo.order_status = 'pending' THEN 1 END) AS pending_orders,
              COUNT(CASE WHEN uo.order_status = 'processing' THEN 1 END) AS processing_orders,
              COUNT(CASE WHEN uo.order_status = 'dispatched' THEN 1 END) AS dispatched_orders,
              COUNT(CASE WHEN uo.order_status = 'delivered' THEN 1 END) AS delivered_orders,
              COUNT(CASE WHEN uo.order_status = 'complete' THEN 1 END) AS complete_orders,
              COUNT(CASE WHEN uo.order_status = 'cancelled' THEN 1 END) AS cancelled_orders,
              COUNT(DISTINCT u.user_id) AS total_customers
            FROM
              user_orders uo
            LEFT JOIN
              user_table u ON uo.user_id = u.user_id;
            ";

  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
      $data = mysqli_fetch_assoc($result);
      $totalOrders = $data['total_orders'];
      $totalPendingOrders = $data['pending_orders'];
      $totalProcessingOrders = $data['processing_orders'];
      $totalDispatchedOrders = $data['dispatched_orders'];
      $totalDeliveredOrders = $data['delivered_orders'];
      $totalCompleteOrders = $data['complete_orders'];
      $totalCancelledOrders = $data['cancelled_orders'];
      $totalCustomers = $data['total_customers'];
  } else {
      $totalOrders = $totalRevenue = $totalCustomers = $totalSales = 0;
  }

 ?>

 <div class="header">
     <div class="left">
         <h1>Dashboard</h1>
     </div>
 </div>

 <!-- Insights -->
 <ul class="insights">
     <li>
         <i class='bx bx-calendar-check'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalOrders, 0, '.', ',') ?>
             </h3>
             <p>Total Orders</p>
         </span>
     </li>
     <li class="pending"><i class='bx bx-time'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalPendingOrders, 0, '.', ',') ?>
             </h3>
             <p>Pending Orders</p>
         </span>
     </li>
     <li class="processing"><i class='bx bxs-package'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalProcessingOrders, 0, '.', ',') ?>
             </h3>
             <p>Processing Orders</p>
         </span>
     </li>
     <li class="dispatched"><i class='bx bxs-truck'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalDispatchedOrders, 0, '.', ',') ?>
             </h3>
             <p>Dispatched Orders</p>
         </span>
     </li>
     <li class="delivered"><i class='bx bx-home'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalDeliveredOrders, 0, '.', ',') ?>
             </h3>
             <p>Delivered Orders</p>
         </span>
     </li>
     <li class="complete"><i class='bx bx-check-circle'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalCompleteOrders, 0, '.', ',') ?>
             </h3>
             <p>Complete Orders</p>
         </span>
     </li>
     <li class="cancelled"><i class='bx bx-x-circle'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalCancelledOrders, 0, '.', ',') ?>
             </h3>
             <p>Cancelled Orders</p>
         </span>
     </li>
 </ul>
 <!-- End of Insights -->

 <div class="bottom-data">
     <div class="orders">
         <div class="header">
             <i class='bx bx-receipt'></i>
             <h3>Recent Orders</h3>
         </div>
         <table>
             <thead>
                 <tr>
                     <th>User</th>
                     <th>Order Date</th>
                     <th>Status</th>
                 </tr>
             </thead>
             <tbody>
               <?php
                 $user_orders = "SELECT
                                   user_orders.*,
                                   u.user_id,
                                   u.user_name,
                                   u.user_image
                                 FROM
                                   user_orders
                                 INNER JOIN
                                   user_table u
                                 ON
                                   user_orders.user_id = u.user_id
                                 ORDER BY
                                   user_orders.order_date DESC
                                 LIMIT 3";
                 $execute_query = mysqli_query($conn, $user_orders);
                 if ($execute_query) {
                   while ($row = mysqli_fetch_assoc($execute_query)) { ?>
                     <tr>
                         <td>
                             <img src="../../users_area/user_images/<?php echo $row['user_image'] ?>">
                             <p><?php echo $row['user_name'] ?></p>
                         </td>
                         <td><?php echo formatDateTime($row['order_date']) ?></td>
                         <td><span class="status <?php echo $row['order_status'] ?>"><?php echo $row['order_status'] ?></span></td>
                     </tr>
                   <?php }} ?>
             </tbody>
         </table>
     </div>

     <!-- Reminders -->
     <div class="reminders">
         <div class="header">
             <i class='bx bx-bell'></i>
             <h3>Alerts</h3>
         </div>
         <ul class="task-list">
           <?php
            if ($notification_rows == 0 && $low_stock_rows == 0) {
              echo "<li><p>No Alerts</p></li>";
            }
             if ($delayed_orders_result && $notification_rows > 0) {
               while ($row = mysqli_fetch_assoc($delayed_orders_result)) {
                 $expectedDate = $row['expected_date']; // Assuming this is in a valid date format
                 $currentDate = new DateTime(); // Current date
                 $expectedDateObj = new DateTime($expectedDate); // Convert the expected date to a DateTime object

                 // Calculate the difference
                 $interval = $currentDate->diff($expectedDateObj);

                 // Get the difference in days
                 $daysDifference = $interval->days;
                 $hoursDifference  = ($daysDifference === 0) ? ($currentDate->getTimestamp() - $expectedDateObj->getTimestamp()) / 3600 : 'null';
                  ?>
                 <li class="not-completed">
                     <div class="task-title">
                         <i class='bx bx-x-circle'></i>
                         <p>Delayed Order <?php echo $row['invoice_number'] ?></p>
                         <p>By <?php echo ($daysDifference !== 0) ? $daysDifference . 'day(s)' : floor($hoursDifference) . 'hour(s)' ?></p>
                     </div>
                     <i class='bx bx-dots-vertical-rounded'></i>
                 </li>
              <?php }
             }
             if ($low_stock_result && $low_stock_rows > 0) {
               while ($row = mysqli_fetch_assoc($low_stock_result)) {
                 $trimmedTitle = (strlen($row['product_title']) > 13)
                                  ? substr($row['product_title'], 0, 13) . "..."
                                  : $row['product_title'];
                  if ($row['instock'] == 0) { ?>
                    <li class="not-completed">
                        <div class="task-title">
                            <i class='bx bx-x-circle'></i>
                            <p>
                             <?php echo $trimmedTitle . ' Out-Of-Stock' ?>
                            </p>
                        </div>
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </li>
               <?php }else { ?>
                 <li class="not-warning">
                     <div class="task-title">
                         <i class='bx bx-info-circle'></i>
                         <p>
                          <?php echo $trimmedTitle . ' Low-On-Stock :' . $row['instock'] ?>
                         </p>
                     </div>
                     <i class='bx bx-dots-vertical-rounded'></i>
                 </li>
               <?php }}} ?>
         </ul>
     </div>

     <!-- End of Reminders-->

 </div>
